<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Slug;
use App\Models\Article;
use App\Models\Log;
use App\Models\Option;
use App\Models\Update;
use Illuminate\Support\Facades\Auth;
use Throwable;

class UpdateController extends Controller
{
    public function index()
    {
        try {
            $articles = Update::all();
            return view('admin.update.index',compact('articles'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Update',
                'message' => 'Update page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'Update page could not be loaded.']);
        }
    }

    public function create()
    { 
        try {
            $categories = Category::where('parent' ,'update')->get();
            $languages = Option::where('key','=','language')->orderBy('id','desc')->get();
            return view('admin.update.create',compact('categories','languages'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Update',
                'message' => 'The Update create page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Update create page could not be loaded.']);
        }
    }

    public function store(Request $request)
    {
        try {
           
            $newsletter = new Update();
            if($request->hasFile('file_id')) {
                $file = $request->file('file_id');
                $filename = $file->getClientOriginalName();
                $file->move(public_path().'/updates/', $filename);
                $newsletter->file_id = $filename;
            }
            $newsletter->user_id = Auth::id();
            $newsletter->category_id = $request->category;
            $newsletter->media_id = $request->media_id ?? 1;
            $newsletter->status = 1;
            $newsletter->title = $request->title;
            $newsletter->content = $request->content;
            $newsletter->eventdate = $request->eventdate;
            $newsletter->save();
         
            return redirect()->route('admin.update.index')->with(['type' => 'success', 'message' =>'Update Saved.']);
        } catch (Throwable $th) {
            
            Log::create([
                'model' => 'Update',
                'message' => 'The Update could not be saved.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Update could not be saved.']);
        }
    }

    public function edit($update)
    { 
        try {
            $categories = Category::where('parent' ,'update')->get();
            $languages = Option::where('key','=','language')->get();
            $value =Update::where('id',$update)->first();
            return view('admin.update.edit',compact('categories','value','languages'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Update',
                'message' => 'The Update edit page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Update edit page could not be loaded.']);
        }
    }

    public function update(Request $request, $id)
    {
        
        
        try {
            $newsletter =Update::find($id);
            if($request->hasFile('file_id')) {
                $file = $request->file('file_id');
                $filename = $file->getClientOriginalName();
                $file->move(public_path().'/updates/', $filename);
                $newsletter->file_id = $filename;
            }
            $newsletter->user_id = Auth::id();
            $newsletter->category_id = $request->category;
            $newsletter->media_id = $request->media_id ?? 1;
            $newsletter->title = $request->title;
            $newsletter->content = $request->content;
            $newsletter->eventdate = $request->eventdate;
            $newsletter->save();
            return redirect()->route('admin.update.index')->with(['type' => 'success', 'message' =>'The Update Has Been Updated.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Update',
                'message' => 'The Update could not be updated.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Update could not be updated.']);
        }
    }

    public function delete($article)
    {
        try {
            $articledata = Update::where('id',$article)->first();
            $articledata->delete();
            return redirect()->route('admin.update.index')->with(['type' => 'success', 'message' =>'Update Moved To Recycle Bin.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Update',
                'message' => 'The Update could not be deleted.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Update could not be deleted.']);
        }
    }

    public function trash()
    {
        try {
            $articles = Update::onlyTrashed()->get();
            return view('admin.update.trash',compact('articles'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Update',
                'message' => 'Update trash page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'Update trash page could not be loaded.']);
        }
    }

    public function recover($id)
    {
        try {
            Update::withTrashed()->find($id)->restore();
            return redirect()->route('admin.update.trash')->with(['type' => 'success', 'message' =>'The Update Recovered.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Update',
                'message' => 'The Update could not be recovered.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Update could not be recovered.']);
        }
    }

    public function destroy($id)
    {
        try {
            $article = Update::withTrashed()->find($id);
            $article->getSlug()->delete();
            $article->forceDelete();
            $filepath = 'updates/'.$article->file_id;
            unlink($filepath);
            return redirect()->route('admin.update.trash')->with(['type' => 'warning', 'message' =>'Post Deleted.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Update',
                'message' => 'The Update could not be destroyed.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The Update could not be destroyed.']);
        }
    }

    public function switch(Request $request)
    {
        try {
            Update::find($request->id)->update([
                'status' => $request->status=="true" ? 1 : 0
            ]);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'Update',
                'message' => 'The Update could not be switched.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
        }
        return $request->status;
    }
}
