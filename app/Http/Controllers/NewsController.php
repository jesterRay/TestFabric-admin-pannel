<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{




    public function index(Request $request){
        try {
            if($request->ajax()){
                $news = (new News)->getNewsForDataTable();
                return $news;
            }
            return view('admin.news.index');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }
    
    public function create(){
        return view('admin.news.create');
    }
    
    public function save(Request $request){
        try {
            // Validate the request
            $validated = $request->validate([
                'news__Title' => 'required|string|max:255',
                'news__Short_Description' => 'required|string',
                'news__Long_Description' => 'required|string',
                'news__Date' => 'required|date',
                'news__Status' => 'required|string|in:Show,Hide',
                'imgfile' => 'required|image|mimes:jpg,jpeg|max:2048', // Validate image
            ]);
    
            // Call the model function to save the data
            $result = (new News)->addNews($validated, $request->file('imgfile'));
    
            if ($result) {
                return redirect()->route('news.index')->with(['success' => 'News added successfully.']);
            }
    
            return redirect()->back()->with(['error' => 'There was an error adding the news.'])->withInput();
        }catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }
    
    public function edit($id){
        try {
            // Call the model function to fetch the data
            $news = (new News)->getNewsById($id);
            
            if (!$news) 
                return redirect()->back()->with(['error' => 'News not found.']);
    
            return view('admin.news.edit')->with([
                'news' => $news,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
    
    public function update(Request $request, $id){
        try {
            // Validate the request
            $validated = $request->validate([
                'news__Title' => 'required|string|max:255',
                'news__Short_Description' => 'required|string',
                'news__Long_Description' => 'required|string',
                'news__Date' => 'required|date',
                'news__Status' => 'required|string|in:Show,Hide',
                'imgfile' => 'image|mimes:jpg,jpeg,png|max:2048', // Validate image
            ]);
    
            // Call the model function to update the record
            $result = (new News)->updateNews($id, $validated, $request->file('imgfile'));
    
            if ($result) {
                return redirect()->route('news.index')->with(['success' => 'News updated successfully.']);
            }
    
            return redirect()->back()->with(['error' => 'There was an error updating the news.'])->withInput();
        }catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }
    
    public function destroy($id){
        try {
            // Call the model function to delete the record
            $result = (new News)->deleteNews($id);
    
            if ($result) {
                return redirect()->route('news.index')->with(['success' => 'News deleted successfully.']);
            }
    
            return redirect()->back()->with(['error' => 'News could not be deleted.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
    




    public function newsLetter(Request $request){
        try {
            if($request->ajax()){
                $news = (new News)->getNewsLetterForDataTable();
                return $news;
            }
            return view('admin.news.news-letter');
        } catch (\Throwable $th) {
            return redirect()->back()->with(['error' => $th->getMessage()]);
        }
    }


}
