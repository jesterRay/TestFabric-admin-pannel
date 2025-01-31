<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class Career extends Model
{
    public function getCareer(){
        try {
            $careers = DB::select("SELECT * FROM testfabrics_career ORDER BY career__Name");
            return $careers;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function getCareerById($id){
        try {
            $result = DB::select("SELECT * FROM testfabrics_career where career__ID = ? ",[$id]);

            if (empty($result)) {
            return null;
            }
            $career = $result[0];

            // Check if the image exists using the findImage function
            $imagePath = findImage($id, 'career_images');

            // Add the image path to the career details, or set it to null if no image found
            $career->imgfile = $imagePath ? $imagePath : null;

            return $career;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // get associtaion and partner and format it into DataTable format 
    public function getCareerForDataTable(){
        try {
            $query = DB::table('testfabrics_career');
            return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $edit_link = route('career.edit', $row->career__ID);
                $delete_link = route('career.destroy', $row->career__ID);
                $view_link = '';
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

    public function addCareer($data, $image){
        try {
            // Insert the record into the database
            $query = "
                INSERT INTO testfabrics_career 
                (career__Name, career__Abbriviation, career__Description)
                VALUES (:name, :abbriviation, :description)
            ";

            DB::insert($query, [
                'name' => $data['career__Name'],
                'abbriviation' => $data['career__Abbriviation'] ?? '',
                'description' => $data['career__Description'] ?? '',
            ]);

            // Get the last inserted ID
            $max_id = DB::getPdo()->lastInsertId();

            // Upload the image using the helper function
            if ($image) {
                $folderName = 'career_images';
                $fileName = imageUpload($folderName, $image, $max_id); // Helper function
                if (!$fileName) {
                    throw new \Exception("Image upload failed.");
                }
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error adding career: " . $e->getMessage());
        }
    }   

    public static function updateCareer($id, $data, $image = null){
        try {
            // Update the record in the database
            $query = "
                UPDATE testfabrics_career 
                SET career__Name = :name,
                    career__Abbriviation = :abbriviation,
                    career__Description = :description
                WHERE career__ID = :id
            ";

            DB::update($query, [
                'name' => $data['career__Name'],
                'abbriviation' => $data['career__Abbriviation'] ?? '',
                'description' => $data['career__Description'] ?? '',
                'id' => $id,
            ]);

            // Handle the image if provided
            if ($image) {
                $folderName = 'career_images';
                $fileName = imageUpload($folderName, $image, $id); // Helper function
                if (!$fileName) {
                    throw new \Exception("Image upload failed.");
                }
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error updating partner: " . $e->getMessage());
        }
    }

    public static function deleteCareer($id){
        try {

            // Delete the record from the database
            $query = "DELETE FROM testfabrics_career WHERE career__ID = :id";
            $result = DB::delete($query, ['id' => $id]);

            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }

            // Retrieve the file path for the career image
            $folderName = 'career_images';

            // helper function to delete the image
            deleteImage($folderName,$id);
            
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting partner: " . $e->getMessage());
        }
    }
}
