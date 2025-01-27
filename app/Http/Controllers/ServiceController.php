<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ServiceController extends Controller
{
    
    public function index(Request $request){
        try {
            if($request->ajax()){
                $services = (new Service)->getServicesForDataTable();
                return $services;
            } 
            
            return view('admin.services.index');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }        

    }

    public function create(){
        return view("admin.services.create");
    }
    
    public function save(Request $request){
        try {
            // validate the incoming data
            $validated = $request->validate([
                'associations_and_partners__Name' => 'required|string|max:255',
                'associations_and_partners__Description' => 'required|string',
                'imgfile' => 'required|image|mimes:jpg|max:2048', // Validate image
            ]);

            $isSaved = (new Service)->saveService($validated, $request->file('imgfile'));
            if($isSaved)
                return redirect()->route('service.index')->with(['success' => 'Service saved successfully']);

            return redirect()->back()->with(['error' => 'Error in saving service'])->withInput();

        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage())->withInput();
        }
    }

    public function edit($id){
        try {

            

            $service = (new Service)->getServiceByID($id);
            if($service)
                return view('admin.services.edit')->with(['service' => $service]);
    
            return back()->withErrors(['error' => 'Service no found']);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());

        }

    }

    public function update(Request $request, $id){
        try {

            // validate the incoming data
            $validated = $request->validate([
                'associations_and_partners__Name' => 'required|string|max:255',
                'associations_and_partners__Description' => 'required|string'
            ]);
            

            $isUpdate = (new Service)->updateService($validated, $id);
            
            if($isUpdate)
                return redirect()->route('service.index')->with('success',"Service Updated Successfully.");
            
            return redirect()->back()->with('error',"Error in updating service. Make sure you edit field before updating.");

        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function destroy($id){
        try {
            $isDelete = (new Service)->deleteService($id);
            if($isDelete)
                return redirect()->route('service.index')->with('success',"Service deleted Successfully.");
            
            return redirect()->back()->with('error',"Error in deleting service.");

        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }
}
