<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;


class AssociationAndPartner extends Model
{
    //  get associtaion and partner
    public function getPartner(){
        try {
            $associationsAndPartners = DB::select("
                SELECT 
                    associations_and_partners__ID as id,
                    associations_and_partners__Name as name,
                    associations_and_partners__Abbriviation as abbreviation,
                    associations_and_partners__Url as url
                FROM 
                    testfabtics_associations_and_partners
                ORDER BY 
                    associations_and_partners__Name
            ");
            return $associationsAndPartners;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getPartnerById($id){
        try {
            // Query to fetch the record
            $query = "
                SELECT *
                FROM testfabtics_associations_and_partners 
                WHERE associations_and_partners__ID = :id
            ";

            $result = DB::select($query, ['id' => $id]);

            // If no partner found, return null
            if (empty($result)) {
                return null;
            }
            $partner = $result[0];

            // Check if the image exists using the findImage function
            $imagePath = findImage($id, 'partners_images');

            // Add the image path to the partner details, or set it to null if no image found
            $partner->imgfile = $imagePath ? $imagePath : null;

            return $partner;
        } catch (\Exception $e) {
            throw new \Exception("Error retrieving partner: " . $e->getMessage());
        }
    }


    // get associtaion and partner and format it into DataTable format 
    public function getPartnerForDataTable(){
        try {
            $associationsAndPartners = $this->getPartner();
            return DataTables::of($associationsAndPartners)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $edit_link = route('association-and-partner.edit', $row->id);
                $delete_link = route('association-and-partner.destroy', $row->id);
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

    public function addPartner($data, $image){
        try {
            // Insert the record into the database
            $query = "
                INSERT INTO testfabtics_associations_and_partners 
                (associations_and_partners__Name, associations_and_partners__Abbriviation, associations_and_partners__Url, associations_and_partners__Description)
                VALUES (:name, :abbriviation, :url, :description)
            ";

            DB::insert($query, [
                'name' => $data['associations_and_partners__Name'],
                'abbriviation' => $data['associations_and_partners__Abbriviation'] ?? '',
                'url' => $data['associations_and_partners__Url'] ?? '',
                'description' => $data['associations_and_partners__Description'] ?? '',
            ]);

            // Get the last inserted ID
            $max_id = DB::getPdo()->lastInsertId();

            // Upload the image using the helper function
            if ($image) {
                $folderName = 'partners_images';
                $fileName = imageUpload($folderName, $image, $max_id); // Helper function
                if (!$fileName) {
                    throw new \Exception("Image upload failed.");
                }
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error adding Association and Partner: " . $e->getMessage());
        }
    }

    public static function updatePartner($id, $data, $image = null){
        try {
            // Update the record in the database
            $query = "
                UPDATE testfabtics_associations_and_partners 
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
                $folderName = 'partners_images';
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

    public static function deletePartner($id){
        try {

            // Delete the record from the database
            $query = "DELETE FROM testfabtics_associations_and_partners WHERE associations_and_partners__ID = :id";
            $result = DB::delete($query, ['id' => $id]);

            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }
            
            // Retrieve the file path for the associated image
            $folderName = 'partners_images';

            // helper function to delete the image
            deleteImage($folderName,$id);
        
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting partner: " . $e->getMessage());
        }
    }
    

}
