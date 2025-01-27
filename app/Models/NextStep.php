<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class NextStep extends Model
{
    // get all next steps data
    public function getNextStep(){
        try {
            $nextSteps = DB::select("SELECT * FROM testfabtics_nextsteps WHERE cat_name = 'nextstep' ORDER BY associations_and_partners__Name");
            return $nextSteps;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    // get the next step data through id
    public function getNextStepById($id){
        try {
            $result = DB::select(
                "SELECT * FROM testfabtics_nextsteps 
                where associations_and_partners__ID = ? AND cat_name = 'nextstep' ",
                [$id]
            );

            if (empty($result)) {
                return null;
            }

            $nextStep = $result[0];

            // Check if the image exists using the findImage function
            $imagePath = findImage($id, 'nextstep_images');

            // Add the image path to the career details, or set it to null if no image found
            $nextStep->imgfile = $imagePath ? $imagePath : null;

            return $nextStep;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // get next step and format it into DataTable format 
    public function getNextStepForDataTable(){
        try {
            $nextSteps = $this->getNextStep();
            return DataTables::of($nextSteps)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $edit_link = route('next-step.edit', $row->associations_and_partners__ID);
                $delete_link = route('next-step.destroy', $row->associations_and_partners__ID);
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

    // add new next step
    public function addNextStep($data, $image){
        try {
            // Insert the record into the database
            $query = "
                INSERT INTO testfabtics_nextsteps 
                (associations_and_partners__Name, associations_and_partners__Abbriviation, 
                    associations_and_partners__Url,associations_and_partners__Description, cat_name)
                VALUES (:name, :abbriviation, :url, :description, :category)
            ";

            DB::insert($query, [
                'name' => $data['associations_and_partners__Name'],
                'abbriviation' => $data['associations_and_partners__Abbriviation'] ?? '',
                'url' => $data['associations_and_partners__Url'] ?? '',
                'description' => $data['associations_and_partners__Description'] ?? '',
                'category' => $data['cat_name'] ?? 'nextstep',
            ]);

            // Get the last inserted ID
            $max_id = DB::getPdo()->lastInsertId();

            // Upload the image using the helper function
            if ($image) {
                $folderName = 'nextstep_images';
                $fileName = imageUpload($folderName, $image, $max_id); // Helper function
                if (!$fileName) {
                    throw new \Exception("Image upload failed.");
                }
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error adding Next Step: " . $e->getMessage());
        }
    }   

    // udpate next step
    public function updateNextStep($id, $data, $image = null){
        try {
            // Update the record in the database
            $query = "
                UPDATE testfabtics_nextsteps 
                SET associations_and_partners__Name = :name,
                    associations_and_partners__Abbriviation = :abbriviation,
                    associations_and_partners__Url = :url,
                    associations_and_partners__Description = :description
                WHERE associations_and_partners__ID = :id
            ";

            DB::update($query, [
                'name' => $data['associations_and_partners__Name'],
                'abbriviation' => $data['associations_and_partners__Abbriviation'] ?? '',
                'url' => $data['associations_and_partners__Url'] ?? '',
                'description' => $data['associations_and_partners__Description'] ?? '',
                'id' => $id,
            ]);

            // Handle the image if provided
            if ($image) {
                $folderName = 'nextstep_images';
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

    // delete next step
    public function deleteNextStep($id){
        try {

            // Delete the record from the database
            $query = "DELETE FROM testfabtics_nextsteps WHERE associations_and_partners__ID = :id";
            $result = DB::delete($query, ['id' => $id]);

            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }

            // Retrieve the file path for the career image
            $folderName = 'nextstep_images';

            // helper function to delete the image
            deleteImage($folderName,$id);
            
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting partner: " . $e->getMessage());
        }
    }
}
