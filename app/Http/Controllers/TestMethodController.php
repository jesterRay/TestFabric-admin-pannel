<?php

namespace App\Http\Controllers;

use App\Models\TestMethod;
use Illuminate\Http\Request;

class TestMethodController extends Controller
{

    public function index(Request $request){
        try {
            
            if($request->ajax()){
                $testMethod = (new TestMethod)->getTestMethodForDataTable();
                return $testMethod;
            }
            return view('admin.test-method.index');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function create(){
        $standards = (new TestMethod)->getStandards();
        return view('admin.test-method.create')->with(['standards' => $standards]);
    }


    public function edit($id){
        try {
            // Call the model function to fetch the data
            $testMethod = (new TestMethod)->getTestMethodById($id);
            $standards = (new TestMethod)->getStandards();
            
            if (!$testMethod) 
                return redirect()->back()->with(['error' => 'Test Method Group not found.']);

            // Return the view with the retrieved data
            return view('admin.test-method.edit')
                    ->with([
                        'testMethod' => $testMethod,
                        'standards' => $standards,
                    ]);

        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function save(Request $request) {
        try {
            // Validate the request
            $validated = $request->validate([
                'methods__Name' => 'required|string|max:255',
                'methods__Description' => 'nullable|string',
                'methods__Standard' => 'required|numeric',
                'methods__Sequence' => 'required|numeric',
                'methods__Status' => 'required|string|in:Show,Hide',
            ]);
    
            // Call the model function to save the data
            $result = (new TestMethod)->addTestMethod($validated);
    
            if ($result) {
                return redirect()->route('test-method.index')->with(['success' => 'Test Method added successfully.']);
            }
    
            return redirect()->back()->with(['error' => 'There was an error adding the Test Method.'])->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }
    
    public function update(Request $request, $id) {
        try {
            // Validate the request
            $validated = $request->validate([
                'methods__Name' => 'required|string|max:255',
                'methods__Description' => 'nullable|string',
                'methods__Standard' => 'required|numeric',
                'methods__Sequence' => 'required|numeric',
                'methods__Status' => 'required|string|in:Show,Hide',
            ]);
    
            // Call the model function to update the record
            $result = (new TestMethod)->updateTestMethod($id, $validated);
    
            if ($result) {
                return redirect()->route('test-method.index')->with(['success' => 'Test Method updated successfully.']);
            }
    
            return redirect()->back()->with(['error' => 'There was an error updating the Test Method.'])->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }
    
    public function destroy($id) {
        try {
            // Call the model function to delete the record
            $result = (new TestMethod)->deleteTestMethod($id);
    
            if ($result) {
                return redirect()->route('test-method.index')->with(['success' => 'Test Method deleted successfully.']);
            }
    
            return redirect()->back()->with(['error' => 'Test Method could not be deleted.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
    
}
