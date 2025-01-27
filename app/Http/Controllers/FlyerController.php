<?php

namespace App\Http\Controllers;

use App\Models\Flyer;
use Illuminate\Http\Request;

class FlyerController extends Controller
{

    public function index(Request $request){
        try {
            
            if($request->ajax()){
                $flyer = (new Flyer)->getFlyerForDataTable();
                return $flyer;
            }
            return view('admin.flyer.index');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function create(){
        try {
            $products = (new Flyer)->getProducts();
            return view('admin.flyer.create')->with(['products' => $products]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function save(Request $request){
        try {
            // Validate the request
            $validated = $request->validate([
                'files1__Description' => 'required|string',
                'files1__Product' => 'nullable|numeric',
                'imgfile' => 'required|mimes:pdf,ppt,xlsx,doc,docx,jpg,txt|max:5120',
            ]);

            // Call the model function to save the data
            $result = (new Flyer)->addFlyer($validated, $request->file('imgfile'));

            if ($result) {
                return redirect()->route('flyer.index')->with(['success' => 'Flyer added successfully.']);
            }

            return redirect()->back()->with(['error' => 'There was an error adding the career.'])->withInput();
        }catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id){
        try {
            // Call the model function to delete the record
            $result = (new Flyer)->deleteFlyer($id);

            if ($result) {
                return redirect()->route('flyer.index')->with(['success' => 'Flyer deleted successfully.']);
            }

            return redirect()->back()->with(['error' => 'Flyer could not be deleted.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

}
