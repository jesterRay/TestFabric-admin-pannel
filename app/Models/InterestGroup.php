<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class InterestGroup extends Model
{
    public function getInterestGroup(){
        try {
            $interestGroups = DB::select("SELECT * FROM testfabtics_product_menu ORDER BY menu_Name ");
            return $interestGroups;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function getInterestGroupById($id){
        try {
            $result = DB::select("SELECT * FROM testfabtics_product_menu where menu_Id = ? ",[$id]);

            if (empty($result)) {
            return null;
            }
            $interestGroup = $result[0];
            $interestGroup->menu_Categories = explode(',', $interestGroup->menu_Categories);
            
            // Check if the image exists using the findImage function
            $imagePath = findImage($id, 'IG_images');

            // Add the image path to the career details, or set it to null if no image found
            $interestGroup->imgfile = $imagePath ? $imagePath : null;

            return $interestGroup;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // get associtaion and partner and format it into DataTable format 
    public function getInterestGroupForDataTable(){
        try {
            $query = DB::table('testfabtics_product_menu');
            return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $edit_link = route('interest-group.edit', $row->menu_Id);
                $delete_link = route('interest-group.destroy', $row->menu_Id);
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

    // get category
    public function getCategory(){
        try {
            $result = DB::select("SELECT category__ID,category__Name FROM testfabrics_category WHERE isEquipment = 0");
            // convert category into array of object containing id and name for dropdown or select option
            $categories = collect($result)->map(function($item){
                return (object) [
                    "id" => $item->category__ID,
                    "name" => $item->category__Name
                ];
            });
            return $categories;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // add interest group and its image
    public function addInterestGroup($data, $image){
        try {

            // convert array containing category into string seprate by comma ','
            $categories = implode(',', $data['menu_Categories']);


            // Insert the record into the database
            $query = "
                INSERT INTO testfabtics_product_menu 
                (menu_Name, menu_Order, menu_Status, menu_Categories)
                VALUES (:name, :order, :status, :categories)
            ";

            DB::insert($query, [
                'name' => $data['menu_Name'],
                'order' => $data['menu_Order'] ?? '',
                'status' => $data['menu_Status'] ?? '',
                'categories' => $categories ?? '',
            ]);

            // Get the last inserted ID
            $max_id = DB::getPdo()->lastInsertId();

            // Upload the image using the helper function
            if ($image) {
                $folderName = 'IG_images';
                $fileName = imageUpload($folderName, $image, $max_id); // Helper function
                if (!$fileName) {
                    throw new \Exception("Image upload failed.");
                }
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error adding Interest Group: " . $e->getMessage());
        }
    }   

    // update interest group
    public function updateInterestGroup($id, $data, $image = null){
        try {
            // convert array containing category into string seprate by comma ','
            $categories = implode(',', $data['menu_Categories']);

            $query = "
                UPDATE testfabtics_product_menu 
                SET menu_Name = :name,
                    menu_Order = :order,
                    menu_Status = :status,
                    menu_Categories = :categories
                WHERE menu_Id = :id
            ";

            DB::update($query, [
                'name' => $data['menu_Name'],
                'order' => $data['menu_Order'] ?? '',
                'status' => $data['menu_Status'] ?? '',
                'categories' => $categories ?? '',
                'id' => $id,
            ]);

            // Handle the image if provided
            if ($image) {
                $folderName = 'IG_images';
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

    // delete interest group
    public function deleteInterestGroup($id){
        try {

            // Delete the record from the database
            $query = "DELETE FROM testfabtics_product_menu WHERE menu_Id = :id";
            $result = DB::delete($query, ['id' => $id]);

            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }

            // Retrieve the file path for the career image
            $folderName = 'IG_images';

            // helper function to delete the image
            deleteImage($folderName,$id);
            
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting partner: " . $e->getMessage());
        }
    }
    
}
