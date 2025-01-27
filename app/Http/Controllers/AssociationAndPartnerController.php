<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssociationAndPartner;

class AssociationAndPartnerController extends Controller
{
    public function index(Request $request){
        try {
            
            if($request->ajax()){
                $associationAndPartner = (new AssociationAndPartner)->getPartnerForDataTable();
                return $associationAndPartner;
            }
            return view('admin.association-and-partner.index');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function create(){
        return view('admin.association-and-partner.create');
    }

    public function save(Request $request){
        try {
            // Validate the request
            $validated = $request->validate([
                'associations_and_partners__Name' => 'required|string|max:255',
                'associations_and_partners__Abbriviation' => 'nullable|string|max:255',
                'associations_and_partners__Url' => 'nullable|url|max:255',
                'associations_and_partners__Description' => 'nullable|string',
                'imgfile' => 'required|image|mimes:jpg,gif|max:2048', // Validate image
            ]);

            // Call the model function to save the data
            $result = (new AssociationAndPartner)->addPartner($validated, $request->file('imgfile'));

            if ($result) {
                return redirect()->route('association-and-partner.index')->with(['success' => 'Record added successfully.']);
            }

            return redirect()->back()->with(['error' => 'There was an error adding the record.'])->withInput();
        }catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit($id){
        try {
            // Call the model function to fetch the data
            $partner = (new AssociationAndPartner)->getPartnerById($id);
            if (!$partner) {
                return redirect()->back()->with(['error' => 'Record not found.']);
            }

            // Return the view with the retrieved data
            return view('admin.association-and-partner.edit')->with(['partner' => $partner]);

        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id){
        try {
            // Validate the request
            $validated = $request->validate([
                'associations_and_partners__Name' => 'required|string|max:255',
                'associations_and_partners__Abbriviation' => 'nullable|string|max:255',
                'associations_and_partners__Url' => 'nullable|url|max:255',
                'associations_and_partners__Description' => 'nullable|string',
                'imgfile' => 'nullable|image|mimes:jpg,gif|max:2048', // Validate image
            ]);

            // Call the model function to update the record
            $result = (new AssociationAndPartner)->updatePartner($id, $validated, $request->file('imgfile'));

            if ($result) {
                return redirect()->route('association-and-partner.index')->with(['success' => 'Record updated successfully.']);
            }

            return redirect()->back()->with(['error' => 'There was an error updating the record.']);
        }catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id){
        try {
            // Call the model function to delete the record
            $result = (new AssociationAndPartner)->deletePartner($id);

            if ($result) {
                return redirect()->route('association-and-partner.index')->with(['success' => 'Record deleted successfully.']);
            }

            return redirect()->back()->with(['error' => 'Record could not be deleted.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

}
