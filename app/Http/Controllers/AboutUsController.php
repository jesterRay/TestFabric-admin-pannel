<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    
    public function index(Request $request){
        try {
            if($request->ajax()){
                $aboutUs = (new AboutUs)->getAboutUsForDataTable();
                return $aboutUs;
            } 
            
            return view('admin.about-us.index');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }        
    
    }
    
    public function create(){
        return view("admin.about-us.create");
    }
    
    public function save(Request $request){
        try {
            $request->validate([
                'name' => 'required',
                'abbrevation' => 'required',
                'description' => 'required',
            ]);
            $name = $request->name ?? '';
            $description = $request->description ?? '';
            $abbrevation = $request->abbrevation ?? '';
            $url = $request->url ?? '';
            $cat_name = 'aboutus';
    
            $isSaved = (new AboutUs)->saveAboutUs($name,$description,$abbrevation,$url,$cat_name);
            if($isSaved)
                return redirect()->route('about-us.index')->with(['success' => 'About Us saved successfully']);
    
            return redirect()->back()->with(['error' => 'Error in saving About Us'])->withInput();
    
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage())->withInput();
        }
    }
    
    public function edit($id){
        try {
            $aboutUs = (new AboutUs)->getAboutUsByID($id);
            if($aboutUs)
                return view('admin.about-us.edit')->with(['aboutUs' => $aboutUs]);
    
            return back()->withErrors(['error' => 'About Us not found']);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
    
        }
    
    }
    
    public function update(Request $request, $id){
        try {
            $request->validate([
                'name' => 'required'
            ]);
            $name = $request->name ?? '';
            $description = $request->description ?? '';
            $abbrevation = $request->abbrevation ?? '';
            $url = $request->url ?? '';
    
            $isUpdate = (new AboutUs)->updateAboutUs($name,$abbrevation,$url,$description,$id);
            
            if($isUpdate)
                return redirect()->route('about-us.index')->with('success',"About Us Updated Successfully.");
            
            return redirect()->back()->with('error',"Error in updating About Us. Make sure you edit field before updating.");
    
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }
    
    public function destroy($id){
        try {
            $isDelete = (new AboutUs)->deleteAboutUs($id);
            if($isDelete)
                return redirect()->route('about-us.index')->with('success',"About Us deleted Successfully.");
            
            return redirect()->back()->with('error',"Error in deleting About Us.");
    
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }
    


}
