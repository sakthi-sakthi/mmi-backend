<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Testimonial;
use App\Models\Update;
use Illuminate\Http\Request;
use App\Models\Slide;
use App\Models\Room;
use App\Models\Socialmedia;
use App\Models\Media;
use App\Models\Image;
use App\Models\Newsevent;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    private $status = 200;
    public function gethomepagedetails(Request $request)
    {
      
        #region Slider Data  
        $Slides = Slide::orderBy('order','asc')->get();

        $SlidesData = [];

        foreach ($Slides as $key => $slides) {
            $data = [
                'id' => $slides->id,
                'title' => $slides->title,
                'content' => $slides->content,
                'image' => asset('slideimages/' . $slides->bg),
                'date' => optional($slides->created_at)->format('d-m-Y'), // Use optional to handle potential null value
            ];
            $SlidesData[] = $data;
        }
        #endregion

        #region Newsletter Data  

        
        $updates = Update::where('status', '1')->orderBy('id', 'desc')->get();
        $updates->each(function ($updates) use ($request) {
            $updates->media_url = $updates->getMedia()->first() ? $updates->getMedia()->first()->getUrl() : null;
            $updates->mediathumb = $updates->getMedia()->first() ? $updates->getMedia()->first()->getUrl('thumb') : null;
            $updates->category_name = $updates->getCategory()->first() ? $updates->getCategory()->first()->title : null;
            $updates->category_id = $updates->getCategory()->first() ? $updates->getCategory()->first()->id : null;
            $updates->created_date = optional($updates->created_at)->format('d-m-Y');
            $updates->eventdate = optional(date_create($updates->eventdate))->format('d-m-Y');
            $updates->file_url = $updates->file_id ? asset('updates/' . $updates->file_id) : null;
           
        });
    
        #endregion


        #region testimonial
        $articles = Testimonial::where('status', '1')->orderby('id', 'desc')->get();
        $articles->each(function ($article) {
            $article->created_date = optional($article->created_at)->format('d-m-Y');
            $article->media_url = $article->getMedia()->first() ? $article->getMedia()->first()->getUrl() : null;
            $article->mediathumb = $article->getMedia()->first() ? $article->getMedia()->first()->getUrl('thumb') : null;
            $article->category_name = $article->getCategory()->first() ? $article->getCategory()->first()->title : null;
            $article->category_id = $article->getCategory()->first() ? $article->getCategory()->first()->id : null;
        });


        #endregion 

        #region youtube Data
        $data = Socialmedia::all();
        #endregion

        #region Allgallery Data
        $Image = Image::select('images.id','images.name', 'images.title', 'images.alt', 'images.path', 'images.created_at', 'categories.title as categoryname', 'categories.id as categoryid')->leftJoin('categories', 'categories.id', '=', 'images.category_id')
            ->orderBy('images.id', 'desc')->get();
        $imagesData = [];

        foreach ($Image as $key => $image) {
            $data = [
                'id' => $image->id,
                'title' => $image->title,
                'alt_tag' => $image->alt,
                'image' => asset($image->path),
                'date' => $image->created_at->format('d-m-Y'),
                'categoryname' => $image->categoryname,
                'categoryid' => $image->categoryid,
            ];
            $imagesData[] = $data;
        }
        #endregion
        
        #region footer contact Data
        $contactpage = Option::where('key', 'contact')->first();

// dd($contactpage);
        if($contactpage != null){
            $arrayData = unserialize($contactpage->value);
            $map = $arrayData['map'];
            $zoom = $arrayData['zoom'];
            $contactdata = [
                'mobile' => $arrayData['phone'],
                'cell' => $arrayData['cell'],
                'email' => $arrayData['email'],
                'address' => $arrayData['address'],
                'googleMapsUrl' => "https://maps.google.com/maps?q=" . $map . "&t=&z=" . $zoom . "&ie=UTF8&iwloc=&output=embed"
            ];
        }
        

        #endregion

        #region Header menu Data
        $results = DB::table('main_menus')
            ->select('main_menus.id', 'main_menus.title as label', 'main_menus.link as url', 
                'submenus.title as submenutitle', 'submenus.link as submenuUrl', 'submenus.id as submenuid', 
                'child_sub_menus.title as childsubmenutitle', 'child_sub_menus.link as childsubmenuUrl', 
                'child_sub_menus.id as childsubmenuid',
                'subchild_sub_menus.title as subchildsubmenutitle', 'subchild_sub_menus.link as subchildsubmenuUrl', 
                'subchild_sub_menus.id as subchildsubmenuid', 'main_menus.status')
            ->leftJoin('submenus', 'submenus.parent_id', 'main_menus.id')
            ->leftJoin('child_sub_menus', 'child_sub_menus.parent_id', 'submenus.id')
            ->leftJoin('subchild_sub_menus', 'subchild_sub_menus.parent_id', 'child_sub_menus.id')
            ->where('main_menus.status', 1)
            ->orderBy('main_menus.Position', 'asc')
            ->orderBy('submenus.Position', 'asc')
            ->orderBy('child_sub_menus.Position', 'asc')
            ->orderBy('subchild_sub_menus.Position', 'asc')
            ->get();
    
        $groupedResults = collect($results)->groupBy('id');
    
        $finalResult = $groupedResults->map(function ($group) {
            $mainMenu = $group->first();
    
            $children = $group->groupBy('submenuid')->map(function ($subGroup) {
                $submenu = $subGroup->first();
    
                $childSubmenus = $subGroup->groupBy('childsubmenuid')->map(function ($childSubGroup) {
                    $childSubmenu = $childSubGroup->first();
    
                    $subchildSubmenus = $childSubGroup->groupBy('subchildsubmenuid')->map(function ($subchildSubGroup) {
                        $subchildSubmenu = $subchildSubGroup->first();
    
                        return [
                            'id' => $subchildSubmenu->subchildsubmenuid,
                            'label' => $subchildSubmenu->subchildsubmenutitle,
                            'url' => $subchildSubmenu->subchildsubmenuUrl,
                        ];
                    })->values()->filter(function ($value) {
                        return $value['id'] !== null;
                    });
    
                    return [
                        'id' => $childSubmenu->childsubmenuid,
                        'label' => $childSubmenu->childsubmenutitle,
                        'url' => $childSubmenu->childsubmenuUrl,
                        'childsubchildren' => $subchildSubmenus->isNotEmpty() ? $subchildSubmenus : null,
                    ];
                })->values()->filter(function ($value) {
                    return $value['id'] !== null;
                });
    
                return [
                    'id' => $submenu->submenuid,
                    'label' => $submenu->submenutitle,
                    'url' => $submenu->submenuUrl,
                    'subchildren' => $childSubmenus->isNotEmpty() ? $childSubmenus : null,
                ];
            })->values()->filter(function ($value) {
                return $value['id'] !== null;
            });
    
            return [
                'id' => $mainMenu->id,
                'label' => $mainMenu->label,
                'url' => $mainMenu->url,
                'children' => $children->isNotEmpty() ? $children : null,
            ];
        })->values()->filter(); 
    
        $response = $finalResult->toArray();

        #endregion

        #region News Data
        $resource = Newsevent::where('status',1)->orderby('id','desc')->get();
        $resource->each(function ($newsevent) {
            $newsevent->media_url = $newsevent->getMedia()->first() ? $newsevent->getMedia()->first()->getUrl() : null;
            $newsevent->mediathumb = $newsevent->getMedia()->first() ? $newsevent->getMedia()->first()->getUrl('thumb') : null;
            $newsevent->category_name = $newsevent->getCategory()->first() ? $newsevent->getCategory()->first()->title : null;
            $newsevent->category_id = $newsevent->getCategory()->first() ? $newsevent->getCategory()->first()->id : null;
            $newsevent->created_date = optional($newsevent->created_at)->format('d-m-Y');
            $newsevent->eventdate = optional(date_create($newsevent->eventdate))->format('d-m-Y');
            $newsevent->file_url = $newsevent->file_id ? asset('UpcomingNews/' . $newsevent->file_id) : null;

        });
        
        #endregion

        $result = [
            'SlidesData' => $SlidesData,
            'newslettersdata' => $updates,
            'testmonialdata' => $articles,
            'yotubedata' => $data,
            'allgallerydata' => $imagesData,
            'footercontactdata' => $contactdata ?? '',
            'headermenudata' => $response,
            'newsdata' => $resource,
        ];

        if (!empty($result)) {
            return response()->json([
                "status" => "success",
                "data" => $result
            ]);
        } else {
            return response()->json([
                "status" => "failed",
                "success" => false,
                "message" => "No records found"
            ]);
        }
    }

}
