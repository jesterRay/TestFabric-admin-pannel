<?php

namespace App\Http\Controllers;

use App\Models\Download;
use Illuminate\Http\Request;

    class DownloadController extends Controller
{
    public function index(Request $request){

        try {

            if($request->ajax()){
                $files = (new Download)->getFilesForDataTable();
                return $files;
            }

            return view('admin.download.index');

        } catch (\Throwable $th) {
            return redirect()->back()->with(['error' => $th->getMessage()]);
        }

    }
    
    public function create(){
        return view('admin.download.create');
    }

    public function save(Request $request){
        try {
            // Validate the request
            $validated = $request->validate([
                'name' => 'required|string|max:30',
                'description' => 'required|string',
                'file' => 'required|file|mimes:jpeg,png,pdf,doc,docx,xls,xlsx|max:10240',
            ]);

            // Call the model's function to handle file saving
            (new Download)->addFile($validated, $request->file('file'));

            return redirect()->route('download.index')->with(['success' => 'File uploaded successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id){
        try {
            // Call the model function to delete the record
            $result = (new Download)->deleteDownloadFile($id);

            if ($result) {
                return redirect()->route('download.index')->with(['success' => 'File deleted successfully.']);
            }

            return redirect()->back()->with(['error' => 'Interest Group could not be deleted.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }


}
