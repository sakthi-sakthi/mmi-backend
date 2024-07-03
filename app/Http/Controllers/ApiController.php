<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use App\Models\Contact;
use App\Models\Slide;
use App\Models\Update;
use App\Models\Socialmedia;
use App\Models\Media;
use App\Models\Page;
use App\Models\Image;
use App\Models\Message;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Throwable;

class ApiController extends Controller
{
    Private $status = 200;
  
    public function storecontact(Request $request)
    {
             try {
                $request->validate([
                    'name' => 'required',
                    'email' => 'required|email',
                    'mobile' => 'required',
                    'message' => 'required',
        
                ]);
               Contact::create($request->all());
                 $bodyContent = [
                     'toName' => $request['name'],
                     'toemail'   => $request['email'],
                     'tomobile'=> $request['mobile'],
                     'tosubject'=> $request['message'],
                 ];
               Mail::to('muni20002raj@gmail.com')->send(new ContactFormMail($bodyContent));
                
                return response()->json(['status' => 'success', 'message'=> 'Request sent successfully']);
                }
                catch (Throwable $th) {
                    return response()->json(['status' => 'failed', 'message'=> $th]);
             }
       
     
    }
 
    
    public function getsliderimages($id){

        
        // $Slides =Slide::select('slides.id','slides.title','slides.content','slides.bg','categories.title as category_name','categories.content as category_description','slides.created_at','slides.category_id')
        // ->leftJoin('categories', 'slides.category_id', '=', 'categories.id')
        // ->where('categories.id', $id)
        // ->get();
    //   dd($Slides);
    $Slides = Slide::orderBy('order','asc')->get();
        $SlidesData = [];
        
        foreach ($Slides as $key => $slides) {
            $data = [
                'id' => $slides->id,
                'title' => $slides->title,
                'content' => $slides->content,
                'image' => asset('slideimages/' . $slides->bg),
                'category_id' =>$slides->category_id
,                'category_name' => $slides->category_name,
                'date' =>  $slides->created_at->format('d-m-Y'),
            ]; 
            $SlidesData[] = $data; 
        }
        if(count($SlidesData) > 0) {
            return response()->json(["status" => $this->status, "success" => true, 
                        "count" => count($SlidesData), "data" => $SlidesData]);
        }
        else {
            return response()->json(["status" => "failed",
            "success" => false, "message" => "Whoops! no record found"]);
        }
    }
    public function getnewsletter(){
        $updates = Update::select(
            'updates.title',
            'updates.file_id',
            'updates.id',
            'updates.content',
            'updates.media_id',
            'updates.created_at',
            'updates.eventdate',
            'updates.category_id',
            'categories.title as category_name'
        )
        ->leftJoin('categories', 'updates.category_id', '=', 'categories.id')
        ->where('updates.status', 1)
        ->get();
    
       $updates->each(function ($update) {
        $mediaUrl = null;
        $update->created_date = $update->created_at->format('d-m-Y');
        $update->eventdate = date("d-m-Y", strtotime($update->eventdate));
        $media = Media::find($update->media_id);
    
        if ($media) {
            $mediaUrl = $media->getUrl();
            $mediathumb = $media->getUrl('thumb');
        }
        $update->file_url = asset('updates/' . $update->file_id);
        
        if($update->media_id != 1){
            $update->media_url = $mediaUrl;
            $update->mediathumb = $mediathumb;
        }
       
    });
    
    return response()->json([
        'success' => true,
        'message' => 'success',
        'data' => $updates,
    ]);
    
    }
    public function getpage($id){
     
        $pages = Page::select(
            'pages.title',
            'pages.id',
            'pages.content',
            'pages.media_id',
            'pages.created_at',
        )
        ->where('pages.status', 1)
        ->where('pages.id', $id)
        ->get();
        $pages->each(function ($page) {
            $mediaUrl = null;
        
            $media = Media::find($page->media_id);
        
            if ($media->id != 1) {
                $mediaUrl = $media->getUrl();
                $mediathumb = $media->getUrl('thumb');
                $page->media_url = $mediaUrl;
                $page->mediathumb = $mediathumb;
            } 
           
        });
        
        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $pages,
        ]);
    }
    

    public function getGalleryimages(){

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
        if(count($Image) > 0) {
            return response()->json(["status" => $this->status, "success" => true, 
                        "count" => count($imagesData), "data" => $imagesData]);
        }
        else {
            return response()->json(["status" => "failed",
            "success" => false, "message" => "Whoops! no record found"]);
        }
    }

    
    public function getVideos()
    {
        // dd('dj');
        $apiKey = 'AIzaSyDzi78e4zOTVdgbANgJV-YYfJ9AFDjK0UA';
        $channelId = 'UCueYcgdqos0_PzNOq81zAFg';

        $client = new Client();
        $response = $client->get("https://www.googleapis.com/youtube/v3/search", [
            'query' => [
                'part' => 'snippet',
                'channelId' => $channelId,
                'order' => 'date',
                'type' => 'video',
                'key' => $apiKey,
            ],
        ]);

        $videos = json_decode($response->getBody()->getContents(), true);

        // Now, $videos contains the response from the YouTube Data API.
        // You can process and display the videos as needed.

      
        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $videos,
        ]);
    }

    public function getyoutubedata(){
           
            $data = Socialmedia::all();
            return response()->json([
                'success' => true,
                'message' => 'success',
                'data' => $data,
            ]);
    }

    public function getcontactpage(){
       $contactpage = Option::where('key','contact')->first();
       
       
       $arrayData = unserialize($contactpage->value);
       $map = $arrayData['map'];
       $zoom = $arrayData['zoom'];
       $contactdata = [
            'mobile' => $arrayData['phone'],
            'cell' => $arrayData['cell'],
            'email' => $arrayData['email'],
            'address' => $arrayData['address'],
            'googleMapsUrl' => "https://maps.google.com/maps?q=".$map."&t=&z=".$zoom."&ie=UTF8&iwloc=&output=embed"
        ];
    
        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $contactdata,
        ]);
       
    }

    public function getmessageslist($id){

        $messages = Message::where('category_id', $id)->get();
        $messages->each(function ($message){

            
            $message->media_url = $message->getMedia()->first() ? $message->getMedia()->first()->getUrl() : null;
            $message->mediathumb = $message->getMedia()->first()? $message->getMedia()->first()->getUrl('thumb') : null;
            $message->categoryname = $message->getCategory()->first()->title;
            $message->file = $message->file_id ? asset('/messages/'.$message->file_id) : null;

            $message->created_date = $message->created_at->format('d-m-Y');
        });

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $messages,
        ]);

    }
    public function getmenus(){
     
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
        return response()->json($response);
    }

    public function gettestimonialdata($id){

        $articles = Testimonial::select(
            'testimonials.title',
            'testimonials.id',
            'testimonials.content',
            'testimonials.media_id',
            'testimonials.created_at',
            'categories.title as category_name',
            'categories.content as category_description'
        )
            ->leftJoin('categories', 'testimonials.category_id', '=', 'categories.id')
            ->where('testimonials.status', 1)
            ->where('categories.id', $id)
            ->get();
         
        $articles->each(function ($article) {
            $mediaUrl = null;
          $media = Media::find($article->media_id);
        
            if ($media) {
                $mediaUrl = $media->getUrl();
                $mediathumb = $media->getUrl('thumb');
                
            }
            if($article->media_id != 1){
                $article->image = $mediaUrl;
                $article->mediathumb = $mediathumb;
            }
            
            $article->date = $article->created_at->format('d-m-Y');
        });
        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'category_name'=> $articles[0]->category_name,
            'category_description'=> $articles[0]->category_description,
            'data' => $articles,
        ]);

    }

}
