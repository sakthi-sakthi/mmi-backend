<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submenu;
use App\Models\ChildSubMenu;
use Illuminate\Http\Request;
use Throwable;
class ChildSubmenuController extends Controller
{
    public function index(){
        try {
            $articles = ChildSubMenu::select('child_sub_menus.*','submenus.title as submenuname')
            ->leftJoin('submenus','submenus.id','child_sub_menus.parent_id')
            ->orderBy('Position','asc')->get();
            return view('admin.homemenu.childsubmenu.index',compact('articles'));
            
        } catch (Throwable $th) {
           
            return redirect()->back()->with(['type' => 'error', 'message' =>'ChildSubMenu page could not be loaded.']);
        }

    }
    public function create()
    {
        try {
            $parentdata = Submenu::all();
            return view('admin.homemenu.childsubmenu.form', compact('parentdata'));
        } catch (Throwable $th) {
           dd($th);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The ChildSubMenu create page could not be loaded.']);
        }
    }
    public function store(Request $request)
    {  
       
        $request->validate([
            'title' => 'required|min:3|max:255',
            'link' => 'required|min:1|max:255',
            'parent_id' =>'required',
        ]);
      try {
           $postion = ChildSubMenu::count()+1;
            $postionval= ChildSubMenu::where('Position','>=',$postion)->count();
            if($postionval == 0 ){
                $request->merge([
                    'Position' => $postion
              ]);
            }
             ChildSubMenu::create([
                'title' => $request->title,
                'link' => $request->link,
                'parent_id' => $request->parent_id,
                'Position' => $request->Position ?? 0

            ]);
              return redirect()->back()->with(['type' => 'success', 'message' =>'ChildSubMenu Created Successfully.']);
        } catch (Throwable $th) {
            
            return redirect()->back()->with(['type' => 'error', 'message' =>'The ChildSubMenu could not be saved.']);
        }
    }

    public function edit($id)
    {
        try {
            $parentdata = Submenu::all();
            $editsubmenu = ChildSubMenu::where('id','=',$id)->first();
            return view('admin.homemenu.childsubmenu.form',compact('parentdata','editsubmenu'));
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'The article edit page could not be loaded.']);
        }
    }


    public function update(Request $request , $id){
         try {
            $data = ChildSubMenu::where('id', $id)->first();
            $data->update([
                'title' => $request->title,
                'link' => $request->link,
                'parent_id' => $request->parent_id,
            ]);
            return redirect()->route('admin.childsubmenu.index')->with(['type' => 'success', 'message' =>'ChildSubMenu Updated Successfully.']);
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'The ChildSubMenu could not be Updated.']);
        }
    }
    public function switch(Request $request)
    {
        try {
            ChildSubMenu::find($request->id)->update([
                'status' => $request->status=="true" ? 1 : 0
            ]);
        } catch (Throwable $th) {
            return $th;
        }
        return $request->status;
    }
    public function editmainmenu(Request $request){
        
        $id = $request->id;
        $data = ChildSubMenu::where('id',$id)->first();
        return response()->json(['status' => true,'data'=> $data]);
    }
    public function delete($id){
        try {
            $data = ChildSubMenu::find($id);
            $data->delete();
            return redirect()->route('admin.childsubmenu.index')->with(['type' => 'success', 'message' =>'ChildSubMenu Moved To Recycle Bin.']);
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'The ChildSubMenu could not be deleted.']);
        }
    }
    public function show()
    {
      
        try {
            $articles = ChildSubMenu::select('child_sub_menus.*','submenus.title as submenuname')
            ->leftJoin('submenus','submenus.id','child_sub_menus.parent_id')
            ->onlyTrashed()
            ->orderBy('Position','asc')->get();
            // $articles = ChildSubMenu::onlyTrashed()->get();
            return view('admin.homemenu.childsubmenu.trash',compact('articles'));
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'ChildSubMenu trash page could not be loaded.']);
        }
    }
    public function recover($id)
    {
        try {
            ChildSubMenu::withTrashed()->find($id)->restore();
            return redirect()->route('admin.childsubmenu.trashed')->with(['type' => 'success', 'message' =>'ChildSubMenu Recovered.']);
        } catch (Throwable $th) {

            return redirect()->back()->with(['type' => 'error', 'message' =>'The ChildSubMenu could not be recovered.']);
        }
    }
    public function destroy($id)
    {
        try {
            $article = ChildSubMenu::withTrashed()->find($id);
            $article->delete();
            $article->forceDelete();
            return redirect()->route('admin.childsubmenu.trashed')->with(['type' => 'warning', 'message' =>'ChildSubMenu Deleted.']);
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'The ChildSubMenu could not be destroyed.']);
        }
    }

    public function updateorder(Request $request){

        foreach ($request->Position as $key => $Position) {
            $main = ChildSubMenu::find($Position['id'])->update(['Position' => $Position['Position']]);
            }
    
            return response()->json(['data'=> 'success']); 
    }
}
