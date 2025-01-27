<?php

namespace App\Http\Controllers;

use App\Models\NextStep;
use Illuminate\Http\Request;

class NextStepController extends Controller
{

    public function index(Request $request){
        try {
            
            if($request->ajax()){
                $nextStep = (new NextStep)->getNextStepForDataTable();
                return $nextStep;
            }
            return view('admin.next-step.index');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function create(){
        return view('admin.next-step.create');
    }

    public function save(Request $request){
        try {
            // Validate the request
            $validated = $request->validate([
                'associations_and_partners__Name' => 'required|string|max:255',
                'associations_and_partners__Abbriviation' => 'required|string|max:255',
                'associations_and_partners__Url' => 'nullable|string',
                'associations_and_partners__Description' => 'required|string',
                'imgfile' => 'required|image|mimes:jpg|max:2048', // Validate image
            ]);

            // Call the model function to save the data
            $result = (new NextStep)->addNextStep($validated, $request->file('imgfile'));

            if ($result) {
                return redirect()->route('next-step.index')->with(['success' => 'Next Step added successfully.']);
            }

            return redirect()->back()->with(['error' => 'There was an error adding the next step.'])->withInput();
        }catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit($id){
        try {
            // Call the model function to fetch the data
            $nextStep = (new NextStep)->getNextStepById($id);
        
            if (!$nextStep) 
                return redirect()->back()->with(['error' => 'Next Step not found.']);

            // Return the view with the retrieved data
            return view('admin.next-step.edit')->with(['nextStep' => $nextStep]);

        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id){
        try {

            // Validate the request
            $validated = $request->validate([
                'associations_and_partners__Name' => 'required|string|max:255',
                'associations_and_partners__Abbriviation' => 'required|string|max:255',
                'associations_and_partners__Url' => 'nullable|string',
                'associations_and_partners__Description' => 'required|string',
                'imgfile' => 'required|image|mimes:jpg|max:2048', // Validate image
            ]);

            // Call the model function to update the record
            $result = (new NextStep)->updateNextStep($id, $validated, $request->file('imgfile'));

            if ($result) {
                return redirect()->route('next-step.index')->with(['success' => 'Next Step updated successfully.']);
            }

            return redirect()->back()->with(['error' => 'There was an error updating the next step.']);
        }catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id){
        try {
            // Call the model function to delete the record
            $result = (new NextStep)->deleteNextStep($id);

            if ($result) {
                return redirect()->route('next-step.index')->with(['success' => 'Next Step deleted successfully.']);
            }

            return redirect()->back()->with(['error' => 'Next Step could not be deleted.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
