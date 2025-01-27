<?php

namespace App\Http\Controllers;

use App\Models\Career;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index(Request $request){
        try {
            
            if($request->ajax()){
                $career = (new Career)->getCareerForDataTable();
                return $career;
            }
            return view('admin.careers.index');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function create(){
        return view('admin.careers.create');
    }

    public function save(Request $request){
        try {
            // Validate the request
            $validated = $request->validate([
                'career__Name' => 'required|string|max:255',
                'career__Abbriviation' => 'nullable|string|max:255',
                'career__Description' => 'nullable|string',
                'imgfile' => 'required|image|mimes:jpg,gif|max:2048', // Validate image
            ]);

            // Call the model function to save the data
            $result = (new Career)->addCareer($validated, $request->file('imgfile'));

            if ($result) {
                return redirect()->route('career.index')->with(['success' => 'Career added successfully.']);
            }

            return redirect()->back()->with(['error' => 'There was an error adding the career.'])->withInput();
        }catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit($id){
        try {
            // Call the model function to fetch the data
            $career = (new Career)->getCareerById($id);
        
            if (!$career) 
                return redirect()->back()->with(['error' => 'Career not found.']);

            // Return the view with the retrieved data
            return view('admin.careers.edit')->with(['career' => $career]);

        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id){
        try {
            // Validate the request
            $validated = $request->validate([
                'career__Name' => 'required|string|max:255',
                'career__Abbriviation' => 'nullable|string|max:255',
                'career__Description' => 'nullable|string',
                'imgfile' => 'nullable|image|mimes:jpg,gif|max:2048', // Validate image
            ]);

            // Call the model function to update the record
            $result = (new Career)->updateCareer($id, $validated, $request->file('imgfile'));

            if ($result) {
                return redirect()->route('career.index')->with(['success' => 'Record updated successfully.']);
            }

            return redirect()->back()->with(['error' => 'There was an error updating the record.']);
        }catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id){
        try {
            // Call the model function to delete the record
            $result = (new Career)->deleteCareer($id);

            if ($result) {
                return redirect()->route('career.index')->with(['success' => 'career deleted successfully.']);
            }

            return redirect()->back()->with(['error' => 'Record could not be deleted.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
