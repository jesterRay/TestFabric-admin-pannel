<?php

namespace App\Models;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class MasterCategory extends Model
{
    // Get all categories
    public function getCategory() {
        try {
            $categories = DB::select("SELECT * FROM testfabrics_category ORDER BY category__Name");
            return $categories;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

  // Get category by ID
    public function getCategoryById($id) {
        try {
            $result = DB::select("SELECT * FROM testfabrics_category WHERE category__ID = ?", [$id]);

            if (empty($result)) {
                return null;
            }

            $category = $result[0];

            $enc_id = suCrypt($id);
            // Check if the category image exists
            $categoryImagePath = $this->getImagePath($enc_id, 'cat_images', null);
            // Check if the banner image exists
            $bannerImagePath = $this->getImagePath($enc_id, 'cat_images', 'banner');

            // Add the image paths to the category details
            $category->imgfile = $categoryImagePath ? $categoryImagePath : null;
            $category->bannerfile = $bannerImagePath ? $bannerImagePath : null;

            return $category;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // Custom method to get the image path
    private function getImagePath($id, $folder, $type = null) {
        if($type == null )
            $filePath = public_path("{$folder}/{$id}.jpg");
        else{
            $filePath = public_path("{$folder}/{$type}_{$id}.jpg");        
        }


        // Check if the file exists in the specified folder
        if (file_exists($filePath)) {
            if($type == null )
                return "{$folder}/{$id}.jpg";
            else
                return "{$folder}/{$type}_{$id}.jpg";        
        }

        return null; // Return null if the image doesn't exist
    }



    // Get categories formatted for DataTable
    public function getCategoryForDataTable() {
        try {

            $query = DB::table('testfabrics_category');
            return DataTables::of($query)
                ->order(function ($query){
                    $query->orderBy('category__Name', 'asc');
                })            
                ->addColumn('action', function($row) {
                    $edit_link = route('category.edit', $row->category__ID);
                    $delete_link = route('category.destroy', $row->category__ID);
                    $view_link = '';
                    return view('components.action-button', 
                                compact('edit_link', 'delete_link', 'view_link'))
                                ->render();
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // Add category and its image
    public function addCategory($data, $image, $bannerImage = null) {
        try {
            // Insert the record into the database
            $query = "
                INSERT INTO testfabrics_category 
                (category__Name, category__Sequence, category__Status, isEquipment, card_view)
                VALUES (:name, :sequence, :status, :equipment, :card_view)
            ";
    
            DB::insert($query, [
                'name' => $data['category__Name'],
                'sequence' => $data['category__Sequence'] ?? 0,
                'status' => $data['category__Status'] ?? 'Show',
                'equipment' => $data['isEquipment'] ?? 0,
                'card_view' => $data['card_view'] ?? 0,
            ]);
    
            // Get the last inserted ID
            $max_id = DB::getPdo()->lastInsertId();
            $encr_id = suCrypt($max_id);
    
            // Save the category image
            if ($image) {
                $imagePath = public_path('cat_images/' . $encr_id . '.jpg');
                $image->move(public_path('cat_images'), $encr_id . '.jpg');
            }
    
            // Save the banner image if provided
            if ($bannerImage) {
                $bannerPath = public_path('cat_images/banner_' . $encr_id . '.jpg');
                $bannerImage->move(public_path('cat_images'), 'banner_' . $encr_id.'.jpg');
            }
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error adding category: " . $e->getMessage());
        }
    }
    

    // Update category
    public function updateCategory($id, $data, $image = null, $bannerImage = null) {
        try {
            // Update the category data
            $query = "
                UPDATE testfabrics_category 
                SET category__Name = :name,
                    category__Sequence = :sequence,
                    category__Status = :status,
                    isEquipment = :equipment
                WHERE category__ID = :id
            ";
    
            DB::update($query, [
                'name' => $data['category__Name'],
                'sequence' => $data['category__Sequence'],
                'status' => $data['category__Status'],
                'equipment' => $data['isEquipment'],
                'id' => $id,
            ]);
            
            $encr_id = suCrypt($id);

            // Handle the category image if provided
            if ($image) {
                // Delete old image if exists
                $oldImagePath = public_path('cat_images/' . $encr_id . '.jpg');
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
    
                // Move the new image to the storage directory
                $image->move(public_path('cat_images'), $encr_id . '.jpg');
            }
    
            // Handle the category banner if provided
            if ($bannerImage) {
                // Delete old banner if exists
                $oldBannerPath = public_path('cat_images/banner_' . $encr_id . '.jpg');
                if (file_exists($oldBannerPath)) {
                    unlink($oldBannerPath);
                }
    
                // Move the new banner to the storage directory
                $bannerImage->move(public_path('cat_images'), 'banner_' . $encr_id . '.jpg');
            }
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error updating category: " . $e->getMessage());
        }
    }
    

    // Delete category
    public function deleteCategory($id) {
        try {
            // Delete the record from the database
            $query = "DELETE FROM testfabrics_category WHERE category__ID = :id";
            $result = DB::delete($query, ['id' => $id]);

            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }

            // Delete the category image if exists
            $categoryImagePath = public_path('cat_images/' . $id . '.jpg');
            if (file_exists($categoryImagePath)) {
                unlink($categoryImagePath);
            }

            // Delete the banner image if exists
            $bannerPath = public_path('cat_images/banner_' . $id . '.jpg');
            if (file_exists($bannerPath)) {
                unlink($bannerPath);
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting category: " . $e->getMessage());
        }
    }

}
