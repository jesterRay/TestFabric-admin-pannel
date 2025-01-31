<?php

namespace App\Http\Controllers;

use App\Models\Standard;
use Illuminate\Http\Request;

class StandardController extends Controller
{

    public function index(Request $request){
        try {
            
            if($request->ajax()){
                $standard = (new Standard)->getStandardForDataTable();
                return $standard;
            }
            return view('admin.standard.index');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function create(){
        return view('admin.standard.create');
    }

    public function save(Request $request){
        try {
            // Validate the request
            $validated = $request->validate([
                'standards__Name' => 'required|string|max:255',
                'standards__Sequence' => 'required|numeric',
                'standards__Status' => 'required|string|in:Show,Hide',
                'imgfile' => 'required|image|mimes:jpg|max:2048', // Validate image
            ]);

            // Call the model function to save the data
            $result = (new Standard)->addStandard($validated, $request->file('imgfile'));

            if ($result) {
                return redirect()->route('standard.index')->with(['success' => 'Standard added successfully.']);
            }

            return redirect()->back()->with(['error' => 'There was an error adding the career.'])->withInput();
        }catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit($id){
        try {
            // Call the model function to fetch the data
            $standard = (new Standard)->getStandardById($id);
            
            if (!$standard) 
                return redirect()->back()->with(['error' => 'Standard Group not found.']);

            // Return the view with the retrieved data
            return view('admin.standard.edit')
                    ->with([
                        'standard' => $standard,
                    ]);

        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id){
        try {
            // Validate the request
            $validated = $request->validate([
                'standards__Name' => 'required|string|max:255',
                'standards__Sequence' => 'required|numeric',
                'standards__Status' => 'required|string|in:Show,Hide',
                'imgfile' => ' image|mimes:jpg,gif|max:2048', // Validate image
            ]);

            // Call the model function to update the record
            $result = (new Standard)->updateStandard($id, $validated, $request->file('imgfile'));

            if ($result) {
                return redirect()->route('standard.index')->with(['success' => 'Record updated successfully.']);
            }

            return redirect()->back()->with(['error' => 'There was an error updating the record.'])->withInput();
        }catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id){
        try {
            // Call the model function to delete the record
            $result = (new Standard)->deleteStandard($id);

            if ($result) {
                return redirect()->route('standard.index')->with(['success' => 'Interest Group deleted successfully.']);
            }

            return redirect()->back()->with(['error' => 'Interest Group could not be deleted.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
    
}
