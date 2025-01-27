<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InterestGroup;

class InterestGroupController extends Controller
{
    public function index(Request $request){
        try {
            
            if($request->ajax()){
                $interestGroups = (new InterestGroup)->getInterestGroupForDataTable();
                return $interestGroups;
            }
            return view('admin.interest-group.index');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function create(){
        $categories = (new InterestGroup)->getCategory();
        return view('admin.interest-group.create')->with(['categories' => $categories]);
    }

    public function save(Request $request){
        try {
            // Validate the request
            $validated = $request->validate([
                'menu_Name' => 'required|string|max:255',
                'menu_Order' => 'required|numeric',
                'menu_Status' => 'required|string|in:Show,Hide',
                'menu_Categories' => 'required',
                'imgfile' => 'required|image|mimes:jpg,gif|max:2048', // Validate image
            ]);

            // Call the model function to save the data
            $result = (new InterestGroup)->addInterestGroup($validated, $request->file('imgfile'));

            if ($result) {
                return redirect()->route('interest-group.index')->with(['success' => 'Career added successfully.']);
            }

            return redirect()->back()->with(['error' => 'There was an error adding the career.'])->withInput();
        }catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit($id){
        try {
            // Call the model function to fetch the data
            $interestGroup = (new InterestGroup)->getInterestGroupById($id);

            $categories = (new InterestGroup)->getCategory();
            
            if (!$interestGroup) 
                return redirect()->back()->with(['error' => 'Interest Group not found.']);

            // Return the view with the retrieved data
            return view('admin.interest-group.edit')
                    ->with([
                        'interestGroup' => $interestGroup,
                        'categories' => $categories
                    ]);

        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id){
        try {
            // Validate the request
            $validated = $request->validate([
                'menu_Name' => 'required|string|max:255',
                'menu_Order' => 'required|numeric',
                'menu_Status' => 'required|string|in:Show,Hide',
                'menu_Categories' => 'required',
                'imgfile' => 'nullable|image|mimes:jpg,gif|max:2048', // Validate image
            ]);

            // Call the model function to update the record
            $result = (new InterestGroup)->updateInterestGroup($id, $validated, $request->file('imgfile'));

            if ($result) {
                return redirect()->route('interest-group.index')->with(['success' => 'Record updated successfully.']);
            }

            return redirect()->back()->with(['error' => 'There was an error updating the record.'])->withInput();
        }catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id){
        try {
            // Call the model function to delete the record
            $result = (new InterestGroup)->deleteInterestGroup($id);

            if ($result) {
                return redirect()->route('career.index')->with(['success' => 'Interest Group deleted successfully.']);
            }

            return redirect()->back()->with(['error' => 'Interest Group could not be deleted.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
