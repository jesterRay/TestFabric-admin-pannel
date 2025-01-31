<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class UploadRelatedDocument extends Model
{
    
    public function getFilesForDataTable(){
        try {
            $query = DB::table('testfabrics_files');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('file',function($row){
                    return $row->files__Name . "." . $row->files__Ext;
                })
                ->addColumn('action', function ($row) {
                    $edit_link = "";
                    $delete_link = route('upload-related-document.destroy', $row->files__ID);
                    return view('components.action-button', compact('edit_link', 'delete_link'))->render();
                })
                ->rawColumns(['file', 'action'])
                ->make(true);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addFile($data, $file){
        try {
            // Step 1: Extract file name and extension
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            // Step 2: Insert file metadata into the database
            $query = "
                INSERT INTO testfabrics_files
                (files__Name, files__Ext, files__Product, files__Description)
                VALUES (:name, :ext, :product, :description)
            ";
            DB::insert($query, [
                'name' => pathinfo($originalName, PATHINFO_FILENAME),
                'ext' => $extension,
                'product' => $data['files__Product'],
                'description' => $data['files__Description'],
            ]);

            // Step 3: Retrieve the last inserted ID
            $lastId = DB::getPdo()->lastInsertId();

            // Step 4: Upload the file using helper and update the file path
            $folderName = 'uploadfiles';
            $filePath = imageUpload($folderName, $file, $lastId); // Helper function

            if (!$filePath) {
                throw new \Exception("File upload failed.");
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error adding file: " . $e->getMessage());
        }
    }



    public function deleteFile($id){
        try {
            $query = "DELETE FROM testfabrics_files WHERE files__ID = :id";
            $result = DB::delete($query, ['id' => $id]);

            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }
            $folderName = 'uploadfiles';
            deleteImage($folderName,$id);
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting file: " . $e->getMessage());
        }
    }

    public function getProduct(){
        try {
            $products = DB::table("testfabrics_product")
                                ->select("product__Name as id","product__Name as name")
                                ->get();
            return $products;

        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function getProductById($id){
        try {
            $products = DB::table("testfabrics_product")
                                ->select("product__Name as id","product__Name as name")
                                ->where('product__ID',$id)
                                ->first();
            return $products ?? null;

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
