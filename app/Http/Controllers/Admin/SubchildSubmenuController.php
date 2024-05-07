<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChildSubMenu;
use App\Models\SubchildSubMenu;
use Illuminate\Http\Request;
use Throwable;
class SubchildSubmenuController extends Controller
{
    public function index(){
        try {
            $articles = SubchildSubMenu::select('subchild_sub_menus.*','child_sub_menus.title as childsubmenuname')
            ->leftJoin('child_sub_menus','child_sub_menus.id','subchild_sub_menus.parent_id')
            ->orderBy('Position','asc')->get();
            return view('admin.homemenu.subchildsubmenu.index',compact('articles'));
            
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'SubchildSubMenu page could not be loaded.']);
        }

    }
    public function create()
    {
        try {
            $parentdata = ChildSubMenu::all();
            return view('admin.homemenu.subchildsubmenu.form', compact('parentdata'));
        } catch (Throwable $th) {
            
            return redirect()->back()->with(['type' => 'error', 'message' =>'The SubchildSubMenu create page could not be loaded.']);
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
           $postion = SubchildSubMenu::count()+1;
            $postionval= SubchildSubMenu::where('Position','>=',$postion)->count();
            if($postionval == 0 ){
                $request->merge([
                    'Position' => $postion
              ]);
            }
             SubchildSubMenu::create([
                'title' => $request->title,
                'link' => $request->link,
                'parent_id' => $request->parent_id,
                'Position' => $request->Position ?? 0

            ]);
              return redirect()->back()->with(['type' => 'success', 'message' =>'SubchildSubMenu Created Successfully.']);
        } catch (Throwable $th) {
            
            return redirect()->back()->with(['type' => 'error', 'message' =>'The SubchildSubMenu could not be saved.']);
        }
    }

    public function edit($id)
    {
        try {
            $parentdata = ChildSubMenu::all();
            $editsubmenu = SubchildSubMenu::where('id','=',$id)->first();
            return view('admin.homemenu.subchildsubmenu.form',compact('parentdata','editsubmenu'));
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'The article edit page could not be loaded.']);
        }
    }


    public function update(Request $request , $id){
         try {
            $data = SubchildSubMenu::where('id', $id)->first();
            $data->update([
                'title' => $request->title,
                'link' => $request->link,
                'parent_id' => $request->parent_id,
            ]);
            return redirect()->route('admin.subchildsubmenu.index')->with(['type' => 'success', 'message' =>'SubchildSubMenu Updated Successfully.']);
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'The SubchildSubMenu could not be Updated.']);
        }
    }
    public function switch(Request $request)
    {
        try {
            SubchildSubMenu::find($request->id)->update([
                'status' => $request->status=="true" ? 1 : 0
            ]);
        } catch (Throwable $th) {
            return $th;
        }
        return $request->status;
    }
    public function editmainmenu(Request $request){
        
        $id = $request->id;
        $data = SubchildSubMenu::where('id',$id)->first();
        return response()->json(['status' => true,'data'=> $data]);
    }
    public function delete($id){
        try {
            $data = SubchildSubMenu::find($id);
            $data->delete();
            return redirect()->route('admin.subchildsubmenu.index')->with(['type' => 'success', 'message' =>'SubchildSubMenu Moved To Recycle Bin.']);
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'The SubchildSubMenu could not be deleted.']);
        }
    }
    public function show()
    {
      
        try {
            $articles = SubchildSubMenu::select('subchild_sub_menus.*','child_sub_menus.title as submenuname')
            ->leftJoin('child_sub_menus','child_sub_menus.id','subchild_sub_menus.parent_id')
            ->onlyTrashed()
            ->orderBy('Position','asc')->get();
            // $articles = SubchildSubMenu::onlyTrashed()->get();
            return view('admin.homemenu.subchildsubmenu.trash',compact('articles'));
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'SubchildSubMenu trash page could not be loaded.']);
        }
    }
    public function recover($id)
    {
        try {
            SubchildSubMenu::withTrashed()->find($id)->restore();
            return redirect()->route('admin.subchildsubmenu.trashed')->with(['type' => 'success', 'message' =>'SubchildSubMenu Recovered.']);
        } catch (Throwable $th) {

            return redirect()->back()->with(['type' => 'error', 'message' =>'The SubchildSubMenu could not be recovered.']);
        }
    }
    public function destroy($id)
    {
        try {
            $article = SubchildSubMenu::withTrashed()->find($id);
            $article->delete();
            $article->forceDelete();
            return redirect()->route('admin.subchildsubmenu.trashed')->with(['type' => 'warning', 'message' =>'SubchildSubMenu Deleted.']);
        } catch (Throwable $th) {
            return redirect()->back()->with(['type' => 'error', 'message' =>'The SubchildSubMenu could not be destroyed.']);
        }
    }

    public function updateorder(Request $request){

        foreach ($request->Position as $key => $Position) {
            $main = SubchildSubMenu::find($Position['id'])->update(['Position' => $Position['Position']]);
            }
    
            return response()->json(['data'=> 'success']); 
    }
}
