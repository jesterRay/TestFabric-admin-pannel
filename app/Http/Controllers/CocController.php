<?php

namespace App\Http\Controllers;

use App\Models\Coc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class CocController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $coc = (new Coc)->getCocForDataTable();
            return $coc;
        }
        return view('admin.coc.index');
    }

    public function delete($id){
        try {
            if((new Coc)->deleteCoc($id))
                return redirect()->route('coc.index');
        } catch (\Throwable $th) {
            return redirect()->back()->with(['error' => $th->getMessage()]);
        }
    }
    
    public function add(){
        return view('admin.coc.add');
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'files__file' => 'required|file|mimes:pdf|max:10240',
            'files__download' => 'required|string|max:255',
        ]);
        // Retrieve the file and other data
        $file = $request->file('files__file');
        $cocName = $request->input('files__download');
        $fullName = $file->getClientOriginalName();
    
        // Define the public directory where files will be stored
        $destinationPath = public_path('cocfiles');
    
        // Ensure the destination directory exists
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true); // Create the directory if it doesn't exist
        }
    
        // Move the file to the desired location
        $file->move($destinationPath, $fullName);
    
        // Insert the record into the database
        $testFabricCOC = new Coc();
        $testFabricCOC->saveCoc($cocName,$fullName);
        
        return redirect()->route('coc.index');
    }

}
