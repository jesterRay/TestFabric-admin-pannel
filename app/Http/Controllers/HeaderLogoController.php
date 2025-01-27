<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;

class HeaderLogoController extends Controller{

    public function index(){
        $header_logo = DB::select("SELECT * FROM testfabtics_header_logo");
        
        return view('admin.header-logo.index')
                ->with(["header_logo" => $header_logo ? $header_logo[0] : null ]);
    }

    public function save(Request $request)
    {
        try {
            // Define the predefined file name
            $newfilename = "header_image"; // Fixed file name

            // Validate the file
            $request->validate([
                'imgfile' => 'required|image|mimes:jpeg,png,jpg|max:5120', // max 5MB
            ]);

            // Check if file is valid
            if ($request->hasFile('imgfile') && $request->file('imgfile')->isValid()) {

                // Get the uploaded file
                $file = $request->file('imgfile');
                $extension = $file->getClientOriginalExtension(); // Get file extension

                // Define the new file name (header_image) and extension
                $fileName = $newfilename . '.' . $extension;

                // Define upload path
                $uploadPath = public_path('upload_header');
                $filePath = $uploadPath . '/' . $fileName;

                // Ensure the folder exists
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0777, true); // Create directory if not exists
                }

                // Move the uploaded file to the specified directory
                $file->move($uploadPath, $fileName);

                // Begin database transaction
                DB::beginTransaction();

                // Delete the existing header logo record
                DB::delete('DELETE FROM testfabtics_header_logo');

                // Insert the new header logo path into the database
                DB::insert('INSERT INTO testfabtics_header_logo (name, date) VALUES (?, ?)', [
                    'upload_header/' . $fileName, // Store relative path
                    now(),
                ]);

                // Commit the transaction
                DB::commit();

                // Success response
                return redirect()->back()->with('success', 'File uploaded successfully!');
                
            } else {
                return redirect()->back()->with('error', 'Invalid file upload!');
            }
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }


}
