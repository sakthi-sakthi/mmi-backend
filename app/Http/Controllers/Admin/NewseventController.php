<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Newsevent;
use Illuminate\Http\Request;

class NewseventController extends Controller
{
    public function index()
    {
        $newsevents = Newsevent::all();
        return view('admin.newsevent.index', compact('newsevents'));
    }

    public function create()
    {
        $categories = Category::where('parent','news_events')->get();
        return view('admin.newsevent.create', compact('categories'));
    }

    public function store(Request $request)
    {
       
          $newsevent = new Newsevent();
        if ($request->hasFile('file_id')) {
                $File = $request->file('file_id');
                $imageName = time() . '_' . $File->getClientOriginalName();
                $File->move(public_path('UpcomingNews'), $imageName);
                $newsevent->file_id = $imageName;
        }
                $newsevent->category_id = $request->category_id;
                $newsevent->media_id = $request->media_id ?? 1;
                $newsevent->title = $request->title;
                $newsevent->content = $request->content;
                $newsevent->status =  1 ;
                $newsevent->eventdate = $request->eventdate;
                $newsevent->save();
        return redirect()->route('admin.news_events.index')->with('success', 'Newsevent created successfully.');
    }

    public function edit($id)
    {
        $newsevent = Newsevent::find($id);
        $categories = Category::where('parent','news_events')->get();
        return view('admin.newsevent.edit', compact('newsevent', 'categories'));
    }

    public function update(Request $request, $id)
    {
       
        $newsevent = Newsevent::find($id);
        if ($request->hasFile('file_id')) {
            $File = $request->file('file_id');
            $imageName = time() . '_' . $File->getClientOriginalName();
            $File->move(public_path('UpcomingNews'), $imageName);
            $newsevent->file_id = $imageName;
        }
        $newsevent->category_id = $request->category_id;
        $newsevent->media_id = $request->media_id ?? 1;
        $newsevent->title = $request->title;
        $newsevent->content = $request->content;
        $newsevent->eventdate = $request->eventdate;
        $newsevent->save();
        return redirect()->route('admin.news_events.index')->with('success', 'Newsevent updated successfully.');
    }
    public function show(){
        $newsevents = Newsevent::onlyTrashed()->get();
        return view('admin.newsevent.trash', compact('newsevents'));

    }

    public function recover($id)
    {
        $newsevent = Newsevent::withTrashed()->find($id);
        $newsevent->restore();
        return redirect()->route('admin.news_events.index')->with('success', 'Newsevent restored successfully.');
    }

    public function destroy($id)
    {
        $newsevent = Newsevent::withTrashed()->find($id);
        $newsevent->forceDelete();
        return redirect()->route('admin.news_events.index')->with('success', 'Newsevent deleted successfully.');
    }

    public function delete($id)
    {
        $newsevent = Newsevent::find($id);
        $newsevent->delete();
        return redirect()->route('admin.news_events.index')->with('success', 'Newsevent deleted successfully.');
    }
    public function updateorder(Request $request){
        $order = $request->id;
        
        Newsevent::find($order)->update([
           'status' => $request->status == true ? 1 : 0
       ]);
   
       return $request->status;
    }
    

 
}
