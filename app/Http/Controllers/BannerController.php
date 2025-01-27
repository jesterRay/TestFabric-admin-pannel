<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{

    public function index(Request $request){
        try {
            if($request->ajax()){
                $banners = (new Banner)->getBannerForDataTable();
                return $banners;
            }
            return view('admin.banner.index');
            


        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $e->getMessage());
            
        }
        

    }


    public function create(){
        return view('admin.banner.create');
    }

    public function save(Request $request){
        try {
            // Validate the request
            $validatedData = $request->validate([
                'files__file' => 'required|file|mimes:jpg,jpeg,png,txt,doc,docx,pdf,ppt,xlsx|max:5120', // 5MB max
                'files__Link' => 'nullable|string|max:255',
                'files__download' => 'nullable|string|max:255',
            ]);
             

            // Pass the validated data to the model
            (new Banner)->createBanner($validatedData);

            return redirect()->route("banner.index")->with('success', 'Record added successfully!');
        } catch (\Exception $e) {
            // Catch and return any exception from the model
            return redirect()->back()->with('error', 'Error adding the banner: ' . $e->getMessage())->withInput();
        }
    }


    public function destroy($id){
        try {
            // Call the model function to delete the record
            $result = (new Banner)->deleteBanner($id);

            if ($result) {
                return redirect()->route('banner.index')->with(['success' => 'Banner deleted successfully.']);
            }

            return redirect()->back()->with(['error' => 'Banner could not be deleted.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
