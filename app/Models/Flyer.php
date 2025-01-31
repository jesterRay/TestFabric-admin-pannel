<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class Flyer extends Model
{
    
    public function getFlyer(){
        try {
            $flyers = DB::select("SELECT * FROM testfabrics_files1 ORDER BY files1__Name ");
            return $flyers;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function getFlyerById($id){
        try {
            $result = DB::select("SELECT * FROM testfabrics_files1 where files1__ID  = ? ",[$id]);

            if (empty($result)) {
            return null;
            }
            $flyer = $result[0];
            
            // Check if the image exists using the findImage function
            $imagePath = findImage($id, 'flyer');

            // Add the image path to the career details, or set it to null if no image found
            $flyer->imgfile = $imagePath ? $imagePath : null;

            return $flyer;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // get Flyer and format it into DataTable format 
    public function getFlyerForDataTable(){
        try {
            $query = DB::table('testfabrics_files1');
            return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $edit_link = "";
                $delete_link = route('flyer.destroy', $row->files1__ID );
                $view_link = "";
                return view('components.action-button', 
                            compact('edit_link', 'delete_link', 'view_link'))
                            ->render();
            })
            ->rawColumns(['action'])
            ->make(true);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // get product from the drop down
    public function getProducts(){
        try {
            
            $products = DB::select("SELECT product__ID as id, product__Name as name FROM testfabrics_product");
            return $products;

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addFlyer($data, $image)    {
        try {
            // Insert the record into the database
            $query = "
                INSERT INTO testfabrics_files1 
                (files1__Name, files1__Ext, files1__Product, files1__Description)
                VALUES (:name, :ext, :product, :description)
            ";
            
            $originalName = $image->getClientOriginalName();
            $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            
            DB::insert($query, [
                'name' => $nameWithoutExt,
                'ext' => $extension,
                'product' => $data['files1__Product'] ?? '',
                'description' => $data['files1__Description']
            ]);

            // Get the last inserted ID
            $max_id = DB::getPdo()->lastInsertId();

            // Upload the file using the helper function
            if ($image) {
                $folderName = 'flyer';
                // Using your existing imageUpload helper function without any modification
                $fileName = imageUpload($folderName, $image, $max_id);
                
                if (!$fileName) {
                    throw new \Exception("File upload failed.");
                }
            }

            return true;

        } catch (\Exception $e) {
            throw new \Exception("Error adding Flyer: " . $e->getMessage());
        }
    }


    public function deleteFlyer($id){
        try {
            // Delete the record from the database
            $query = "DELETE FROM testfabrics_files1 WHERE files1__ID  = :id";
            $result = DB::delete($query, ['id' => $id]);

            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }

            // Retrieve the file path for the career image
            $folderName = 'flyer';

            // helper function to delete the image
            deleteImage($folderName,$id);
            
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
