<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{

    public function index(Request $request) {
        try {
            if ($request->ajax()) {
                $pages = (new Page)->getPagesForDataTable();
                return $pages;
            }
            return view('admin.page.index');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    
    public function create() {
        return view('admin.page.create');
    }
    
    public function save(Request $request) {
        try {
            // Validate the request
            $validated = $request->validate([
                'page_name' => 'required|string|max:225',
                'page_title' => 'required|string|max:225',
                'page_keywords' => 'required|string',
                'page_description' => 'required|string',
                'page_long_content' => 'required|string',
            ]);
    
            // Call the model function to save the data
            $result = (new Page)->addPage($validated);
    
            if ($result) {
                return redirect()->route('page.index')->with(['success' => 'Page added successfully.']);
            }
    
            return redirect()->back()->with(['error' => 'There was an error adding the page.'])->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }
    
    public function edit($id) {
        try {
            // Call the model function to fetch the data
            $page = (new Page)->getPageById($id);
    
            if (!$page) {
                return redirect()->back()->with(['error' => 'Page not found.']);
            }
    
            // Return the view with the retrieved data
            return view('admin.page.edit')->with([
                'page' => $page,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
    
    public function update(Request $request, $id) {
        try {
            // Validate the request
            $validated = $request->validate([
                'page_name' => 'required|string|max:50',
                'page_title' => 'required|string|max:200',
                'page_keywords' => 'required|string',
                'page_description' => 'required|string',
                'page_long_content' => 'required|string',
            ]);
    
            // Call the model function to update the record
            $result = (new Page)->updatePage($id, $validated);
    
            if ($result) {
                return redirect()->route('page.index')->with(['success' => 'Page updated successfully.']);
            }
    
            return redirect()->back()->with(['error' => 'There was an error updating the page.'])->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }
    
    public function destroy($id) {
        try {
            // Call the model function to delete the record
            $result = (new Page)->deletePage($id);
    
            if ($result) {
                return redirect()->route('page.index')->with(['success' => 'Page deleted successfully.']);
            }
    
            return redirect()->back()->with(['error' => 'Page could not be deleted.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
    
    public function home(){
        try {
            $page = (new Page)->getHomePage();
            return view('admin.page.home-page-section-2')->with([ "page" => $page ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
    
    public function homeUpdate(Request $request, $id){
        try {
            $validated = $request->validate([
                'heading' => 'required|string|max:225',
                'text' => 'required|string',
            ]);

            (new Page)->updateHomePage($id,$validated);

            return redirect()->route("page.index")->with([ "success" => "Update Successfully" ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with(['error' => $th->getMessage()])->withInput();
        }
    }





}
