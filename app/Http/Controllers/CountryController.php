<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Continent;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    
    public function index(Request $request,$id){
        try {
            
            if($request->ajax()){
                $countries = (new Country)->getCountryForDataTable($id);
                return $countries;
            }

            $continent = (new Continent)->getContinentById($id);
            return view('admin.country.index')->with(['continent' => $continent]);
    
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }
    
    public function create(){
        try {
            $continent = (new Country)->getContinents();
            if($continent)
                return view('admin.country.create')->with(['continent' => $continent]);

                return redirect()->back()->with(['error' => "Error in Fetching Continent"]);
        } catch (\Throwable $th) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
            
        }

    }
    
    public function save(Request $request){
        try {
            // Validate the request
            $validated = $request->validate([
                'countries__Name' => 'required|string|max:255',
                'countries__Map_ID' => 'required|numeric',
                'countries__Status' => 'nullable|in:Hide,Show',
                'imgfile' => 'required|image|mimes:jpg|max:2048', // Validate image
            ]);

            // Call the model function to save the data
            $result = (new Country)->addCountry($validated, $request->file('imgfile'));
            $continent_id = $request->countries__Map_ID;
            if ($result) {
                return redirect()
                        ->route('country.index',['id' => $continent_id])
                        ->with(['success' => 'Country added successfully.']);
            }

            return redirect()->back()->with(['error' => 'There was an error adding the career.'])->withInput();
        }catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }
    
    public function edit($id){
        try {
            $continent = (new Country)->getContinents();
            $country = (new Country)->getCountryById($id);         
            if (!$continent) 
                return redirect()->back()->with(['error' => 'Continent not found.']);
    

            return view('admin.country.edit')
                    ->with([
                        'country' => $country,
                        'continent' => $continent
                    ]);
    
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
    
    public function update(Request $request, $id){
        try {
            // Validate the request
            $validated = $request->validate([
                'countries__Name' => 'required|string|max:255',
                'countries__Map_ID' => 'required|numeric',
                'countries__Status' => 'nullable|in:Hide,Show',
                'imgfile' => 'image|mimes:jpg|max:2048', // Validate image
            ]);
    
            // Call the model function to update the record
            $result = (new Country)->updateCountry($id, $validated,$request->file('imgfile'));
            
            $countinent_id = $request->countries__Map_ID;
            if ($result) {
                return redirect()
                        ->route('country.index',['id' => $countinent_id])
                        ->with(['success' => 'Country updated successfully.']);
            }
    
            return redirect()->back()->with(['error' => 'There was an error updating the record.'])->withInput();
        }catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }
    
    public function destroy($id){
        try {
            // Call the model function to delete the record
            $result = (new Country)->deleteCountry($id);
    
            if ($result) {
                return redirect()->back()->with(['success' => 'Country deleted successfully.']);
            }
    
            return redirect()->back()->with(['error' => 'Country could not be deleted.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
    
}
