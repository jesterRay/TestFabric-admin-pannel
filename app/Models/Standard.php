<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class Standard extends Model
{

    public function getStandard(){
        try {
            $standards = DB::select("SELECT * FROM testfabrics_standards ORDER BY standards__Name ");
            return $standards;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function getStandardById($id){
        try {
            $result = DB::select("SELECT * FROM testfabrics_standards where standards__ID = ? ",[$id]);

            if (empty($result)) {
            return null;
            }
            $standard = $result[0];
            
            // Check if the image exists using the findImage function
            $imagePath = findImage($id, 'standards_images');

            // Add the image path to the career details, or set it to null if no image found
            $standard->imgfile = $imagePath ? $imagePath : null;

            return $standard;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // get Standard and format it into DataTable format 
    public function getStandardForDataTable(){
        try {
            $query = DB::table('testfabrics_standards');
            return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $edit_link = route('standard.edit', $row->standards__ID);
                $delete_link = route('standard.destroy', $row->standards__ID);
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

    // add Standard and its image
    public function addStandard($data, $image){
        try {

            // Insert the record into the database
            $query = "
                INSERT INTO testfabrics_standards 
                (standards__Name, standards__Sequence, standards__Status)
                VALUES (:name, :sequence, :status)
            ";

            DB::insert($query, [
                'name' => $data['standards__Name'],
                'sequence' => $data['standards__Sequence'] ?? '',
                'status' => $data['standards__Status'] ?? '',
            ]);

            // Get the last inserted ID
            $max_id = DB::getPdo()->lastInsertId();

            // Upload the image using the helper function
            if ($image) {
                $folderName = 'standards_images';
                $fileName = imageUpload($folderName, $image, $max_id); // Helper function
                if (!$fileName) {
                    throw new \Exception("Image upload failed.");
                }
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error adding Standard Image: " . $e->getMessage());
        }
    }   

    // update Standard
    public function updateStandard($id, $data, $image = null){
        try {

            $query = "
                UPDATE testfabrics_standards 
                SET standards__Name = :name,
                    standards__Sequence = :sequence,
                    standards__Status = :status
                WHERE standards__ID = :id
            ";

            DB::update($query, [
                'name' => $data['standards__Name'],
                'sequence' => $data['standards__Sequence'] ?? '',
                'status' => $data['standards__Status'] ?? '',
                'id' => $id,
            ]);

            // Handle the image if provided
            if ($image) {
                $folderName = 'standards_images';
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

    // delete Standard
    public function deleteStandard($id){
        try {

            // Delete the record from the database
            $query = "DELETE FROM testfabrics_standards WHERE standards__ID = :id";
            $result = DB::delete($query, ['id' => $id]);

            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }

            // Retrieve the file path for the career image
            $folderName = 'standards_images';

            // helper function to delete the image
            deleteImage($folderName,$id);
            
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting standard: " . $e->getMessage());
        }
    }
    
}
