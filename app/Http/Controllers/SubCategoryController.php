<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    // Show Subcategories
    public function index(Request $request){
        try {
            if ($request->ajax()) {
                $subcategories = (new SubCategory)->getSubCategoriesForDataTable();
                return $subcategories;
            }
            return view('admin.sub-category.index');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    // Show Create Form
    public function create(){
        try {
            $categories = (new SubCategory)->getCategories();
            return view('admin.sub-category.create')->with([
                'categories' => $categories
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    // Save Subcategory
    public function save(Request $request){
        try {
            // Validate the request
            $validated = $request->validate([
                'subcategory__Name' => 'required|string|max:255',
                'subcategory__Category_Name' => 'required|integer',
                'subcategory__Sequence' => 'required|numeric',
                'subcategory__Status' => 'required|string|in:Show,Hide',
                'isEquipment' => 'required|in:0,1',
                'card_view' => 'nullable|boolean',
                'subcategory__image' => 'required|image|mimes:jpg|max:2048', // Validate subcategory image
                'banner__image' => 'required|image|mimes:jpg|max:2048' // Validate banner image
            ]);

            // Call model function to save the data
            $result = (new SubCategory)->addSubCategory($validated, $request->file('subcategory__image'), $request->file('banner__image'));

            if ($result) {
                return redirect()->route('sub-category.index')->with(['success' => 'Subcategory added successfully.']);
            }

            return redirect()->back()->with(['error' => 'There was an error adding the subcategory.'])->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }

    // Edit Subcategory
    public function edit($id){
        try {
            // Fetch the subcategory data by ID
            $subcategory = (new SubCategory)->getSubCategoryById($id);
            $categories = (new SubCategory)->getCategories();

            if (!$subcategory) {
                return redirect()->back()->with(['error' => 'Subcategory not found.']);
            }

            return view('admin.sub-category.edit')
                ->with([
                    'subcategory' => $subcategory,
                    'categories' => $categories
                ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    // Update Subcategory
    public function update(Request $request, $id){
        try {
            // Validate the request
            $validated = $request->validate([
                'subcategory__Name' => 'required|string|max:255',
                'subcategory__Category_Name' => 'required|integer',
                'subcategory__Sequence' => 'required|numeric',
                'subcategory__Status' => 'required|string|in:Show,Hide',
                'isEquipment' => 'required|in:0,1',
                'subcategory__image' => 'nullable|image|mimes:jpg|max:2048', // Optional subcategory image
                'banner__image' => 'nullable|image|mimes:jpg|max:2048' // Optional banner image
            ]);

            // Call the model function to update the subcategory
            $result = (new SubCategory)->updateSubCategory(
                $id,
                $validated,
                $request->file('subcategory__image'),
                $request->file('banner__image')
            );

            if ($result) {
                return redirect()->route('sub-category.index')->with(['success' => 'Subcategory updated successfully.']);
            }

            return redirect()->back()->with(['error' => 'There was an error updating the subcategory.'])->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }


    // Destroy Subcategory
    public function destroy($id)
    {
        try {
            // Call the model to delete the subcategory
            $result = (new SubCategory)->deleteSubCategory($id);

            if ($result) {
                return redirect()->route('sub-category.index')->with(['success' => 'Subcategory deleted successfully.']);
            }

            return redirect()->back()->with(['error' => 'Subcategory could not be deleted.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
