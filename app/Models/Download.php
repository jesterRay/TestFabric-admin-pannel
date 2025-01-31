<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class Download extends Model
{

    public function getFilesForDataTable(){
        
        try {
            $query = DB::table('testfabrics_downloads');

            return DataTables::of($query)          
                ->addColumn('action', function($row) {
                    $edit_link = "";
                    $delete_link = route('download.destroy', $row->files__ID);
                    $view_link = '';
                    return view('components.action-button', 
                                compact('edit_link', 'delete_link', 'view_link'))
                                ->render();
                })
                ->addColumn('download_name', function($row) {
                    return $row->files__Name . '.' . $row->files__Ext;
                })
                ->rawColumns(['action','download_name'])
                ->addIndexColumn()
                ->make(true);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addFile($data, $file){
        try {
            // Extract file details
            $fileName = $file->getClientOriginalName(); // Original file name
            $fileExtension = $file->getClientOriginalExtension(); // File extension
    
            // Prepare data to insert
            $insertData = [
                'files__Name' => $data['name'],
                'files__Ext' => $fileExtension,
                'files__Description' => $data['description'] ?? '',
                'files__download_name' => $fileName,
                'files__Product' => '', // nullable
                'files__picture' => '', // nullable
            ];
    
            // Insert the data and get the last inserted ID
            $lastId = DB::table('testfabrics_downloads')->insertGetId($insertData);
    
            if (!$lastId) {
                throw new \Exception("Error saving file record.");
            }
    
            // Use the helper function to upload the file
            $folderName = 'downloadfiles';
            $uploadedFileName = imageUpload($folderName, $file, $lastId);
    
            if (!$uploadedFileName) {
                throw new \Exception("File upload failed.");
            }
    
            // Update the database with the uploaded file name
            DB::table('testfabrics_downloads')
                ->where('files__ID', $lastId)
                ->update(['files__download_name' => $uploadedFileName]);
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error handling file: " . $e->getMessage());
        }
    }

      // delete Standard
      public function deleteDownloadFile($id){
        try {

            // Delete the record from the database
            $query = "DELETE FROM testfabrics_downloads WHERE files__ID = :id";
            $result = DB::delete($query, ['id' => $id]);

            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }

            // Retrieve the file path for the career image
            $folderName = 'downloadfiles';

            // helper function to delete the image
            deleteImage($folderName,$id);
            
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting standard: " . $e->getMessage());
        }
    }
    
    
}
