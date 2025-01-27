<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterCategory;

class MasterCategoryController extends Controller
{

    public function index(Request $request){
        try {
            if ($request->ajax()) {
                $categories = (new MasterCategory)->getCategoryForDataTable();
                return $categories;
            }
            return view('admin.master-category.index');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    
    public function create() {
        return view('admin.master-category.create');
    }
    
    public function save(Request $request) {
        try {
            // Validate the request
            $validated = $request->validate([
                'category__Name' => 'required|string|max:255',
                'category__Sequence' => 'required|numeric',
                'category__Status' => 'required|string|in:Show,Hide',
                'imgfile' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048', // Validate category image
                'category_banner' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Optional category banner image
                'isEquipment' => 'required|in:0,1', // Ensure it's either '0' (No) or '1' (Yes)
            ]);
    
            // Call the model function to save the data
            $result = (new MasterCategory)->addCategory($validated, $request->file('imgfile'), $request->file('category_banner'));
    
            if ($result) {
                return redirect()->route('category.index')->with(['success' => 'Category added successfully.']);
            }
    
            return redirect()->back()->with(['error' => 'There was an error adding the category.'])->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }
    
    
    public function edit($id) {
        try {
            // Call the model function to fetch the data
            $category = (new MasterCategory)->getCategoryById($id);
    
            if (!$category) {
                return redirect()->back()->with(['error' => 'Category not found.']);
            }
    
            // Return the view with the retrieved data, including images
            return view('admin.master-category.edit')
                ->with([
                    'category' => $category
                ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
    
    
    public function update(Request $request, $id) {
        try {
            // Validate the request
            $validated = $request->validate([
                'category__Name' => 'required|string|max:255',
                'category__Sequence' => 'required|numeric',
                'category__Status' => 'required|string|in:Show,Hide',
                'isEquipment' => 'required|in:0,1', // Ensure isEquipment is either 0 or 1
                'imgfile' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Validate image
                'category_banner' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Validate category banner
            ]);
    
            // Call the model function to update the record
            $result = (new MasterCategory)->updateCategory($id, $validated, $request->file('imgfile'), $request->file('category_banner'));
    
            if ($result) {
                return redirect()->route('category.index')->with(['success' => 'Category updated successfully.']);
            }
    
            return redirect()->back()->with(['error' => 'There was an error updating the category.'])->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }
    
    
    public function destroy($id) {
        try {
            // Call the model function to delete the record
            $result = (new MasterCategory)->deleteCategory($id);
    
            if ($result) {
                return redirect()->route('category.index')->with(['success' => 'Category deleted successfully.']);
            }
    
            return redirect()->back()->with(['error' => 'Category could not be deleted.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
    
}
