<?php

namespace App\Http\Controllers;

use App\Models\Continent;
use Illuminate\Http\Request;

class ContinentController extends Controller
{

    public function index(Request $request){
        try {
            
            if($request->ajax()){
                $continents = (new Continent)->getContinentForDataTable();
                return $continents;
            }
            return view('admin.continent.index');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function create(){
        return view('admin.continent.create');
    }

    public function save(Request $request){
        try {
            // Validate the request
            $validated = $request->validate([
                'map__Name' => 'required|string|max:255',
                'map__Status' => 'required|string|in:Show,Hide',
                'map__Sorting' => 'nullable|numeric',
            ]);

            // Call the model function to save the data
            $result = (new Continent)->addContinent($validated);

            if ($result) {
                return redirect()->route('continent.index')->with(['success' => 'Standard added successfully.']);
            }

            return redirect()->back()->with(['error' => 'There was an error adding the career.'])->withInput();
        }catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit($id){
        try {
            // Call the model function to fetch the data
            $continent = (new Continent)->getContinentById($id);
            
            if (!$continent) 
                return redirect()->back()->with(['error' => 'Continent Group not found.']);

            // Return the view with the retrieved data
            return view('admin.continent.edit')
                    ->with([
                        'continent' => $continent,
                    ]);

        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id){
        try {
            // Validate the request
            $validated = $request->validate([
                'map__Name' => 'required|string|max:255',
                'map__Status' => 'required|string|in:Show,Hide',
                'map__Sorting' => 'nullable|numeric',
            ]);

            // Call the model function to update the record
            $result = (new Continent)->updateContinent($id, $validated);

            if ($result) {
                return redirect()->route('continent.index')->with(['success' => 'Continent updated successfully.']);
            }

            return redirect()->back()->with(['error' => 'There was an error updating the record.'])->withInput();
        }catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id){
        try {
            // Call the model function to delete the record
            $result = (new Continent)->deleteContinent($id);

            if ($result) {
                return redirect()->route('continent.index')->with(['success' => 'Continent deleted successfully.']);
            }

            return redirect()->back()->with(['error' => 'Continent could not be deleted.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
