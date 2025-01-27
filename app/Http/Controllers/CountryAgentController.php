<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CountryAgent;
use Illuminate\Http\Request;

class CountryAgentController extends Controller
{
    public function index(Request $request){
        try {
            if ($request->ajax()) {
                $agents = (new CountryAgent)->getAgentsForDataTable();
                return $agents;
            }
            return view('admin.country-agent.index');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function create(){
        try {
            $countries = (new Country)->getCountriesForSelect();
            if(!$countries)
                return redirect()->back()->with('error', "No country found");            

            return view('admin.country-agent.create')->with(['countries' => $countries]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());            
        }
    }


    public function save(Request $request){
        try {
            // Validate the request
            $validated = $request->validate([
                'agent__Name' => 'required|string|max:100',
                'agent__Email' => 'required|email|max:100|unique:testfabrics_country_agents,agent__Email',
                'agent__Password' => 'required|string|max:30',
                'agent__Country' => 'required|integer',
                'agent__Website' => 'nullable|url|max:255',
                'agent__Address' => 'nullable|string',
                'agent__Phone' => 'nullable|string|max:50',
                'agent__Fax' => 'nullable|string|max:50',
                'agent__Latitude' => 'nullable|string|max:100',
                'agent__Longitude' => 'nullable|string|max:100',
                'agent__Flag' => 'nullable|string|max:50',
                'agent__Status' => 'required|in:Show,Hide',
            ]);

            $result = (new CountryAgent)->addAgent($validated);

            if ($result) {
                return redirect()->route('country-agent.index')->with(['success' => 'Country Agent added successfully.']);
            }

            return redirect()->back()->with(['error' => 'There was an error adding the agent.'])->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit($id){
        try {
            $agent = (new CountryAgent)->getAgentById($id);

            if (!$agent) {
                return redirect()->back()->with(['error' => 'Country Agent not found.']);
            }

            $countries = (new Country)->getCountriesForSelect();
            if(!$countries)
                return redirect()->back()->with('error', "No country found"); 

            return view('admin.country-agent.edit')->with([
                'agent' => $agent,
                'countries' => $countries
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id){
        try {
            $validated = $request->validate([
                'agent__Name' => 'required|string|max:100',
                'agent__Email' => 'required|email|max:100|unique:testfabrics_country_agents,agent__Email,' . $id . ',agent__ID',
                'agent__Password' => 'nullable|string|max:30',
                'agent__Country' => 'required|integer',
                'agent__Website' => 'nullable|url|max:255',
                'agent__Address' => 'nullable|string',
                'agent__Phone' => 'nullable|string|max:50',
                'agent__Fax' => 'nullable|string|max:50',
                'agent__Latitude' => 'nullable|string|max:100',
                'agent__Longitude' => 'nullable|string|max:100',
                'agent__Flag' => 'nullable|string|max:50',
                'agent__Status' => 'required|in:Show,Hide',
            ]);

            $result = (new CountryAgent)->updateAgent($id, $validated);

            if ($result) {
                return redirect()->route('country-agent.index')->with(['success' => 'Agent updated successfully.']);
            }

            return redirect()->back()->with(['error' => 'There was an error updating the agent.'])->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id){
        try {
            $result = (new CountryAgent)->deleteAgent($id);

            if ($result) {
                return redirect()->route('country-agent.index')->with(['success' => 'Agent deleted successfully.']);
            }

            return redirect()->back()->with(['error' => 'Agent could not be deleted.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

}
