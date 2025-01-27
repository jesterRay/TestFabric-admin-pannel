<?php

namespace App\Models;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    
    public function getCategories(){
        try {
            $categories = DB::table('testfabrics_category')
                                ->select("category__ID as id","category__Name as name")
                                ->get();
            return $categories;
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function getSubCategoriesForDataTable(){
        try {
            $query = DB::table('testfabrics_subcategory')
                ->join('testfabrics_category', 'testfabrics_subcategory.subcategory__Category_Name', '=', 'testfabrics_category.category__ID')
                ->select(
                    'testfabrics_subcategory.*',
                    'testfabrics_category.category__Name as category__Name' // Alias for category name
                );
    
            return DataTables::of($query)
                ->order(function ($query) {
                    $query->orderBy('subcategory__Name', 'asc');
                })
                ->addColumn('action', function ($row) {
                    $edit_link = route('sub-category.edit', $row->subcategory__ID);
                    $delete_link = route('sub-category.destroy', $row->subcategory__ID);
                    return view('components.action-button', compact('edit_link', 'delete_link'))->render();
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    // Add Subcategory
    public function addSubCategory($data, $subcategoryImage, $bannerImage){
        try {
            // Insert the record into the database
            $query = "
                INSERT INTO testfabrics_subcategory 
                (subcategory__Name, subcategory__Category_Name, subcategory__Sequence, subcategory__Status, isEquipment, card_view,subcategory__image)
                VALUES (:name, :category_name, :sequence, :status, :equipment, :card_view, :image)
            ";

            DB::insert($query, [
                'name' => $data['subcategory__Name'],
                'category_name' => $data['subcategory__Category_Name'],
                'sequence' => $data['subcategory__Sequence'],
                'status' => $data['subcategory__Status'],
                'equipment' => $data['isEquipment'],
                'card_view' => $data['card_view'] ?? 0,
                'image' => ' ' 
            ]);

            $max_id = DB::getPdo()->lastInsertId();
            $enc_id = suCrypt($max_id);

            // Save the subcategory image
            if ($subcategoryImage) {
                $imagePath = public_path('subcat_images/' . $enc_id . '.jpg');
                $subcategoryImage->move(public_path('subcat_images'), $enc_id . '.jpg');
            }

            // Save the banner image
            if ($bannerImage) {
                $bannerPath = public_path('subcat_images/banner_' . $enc_id . '.jpg');
                $bannerImage->move(public_path('subcat_images'), 'banner_' . $enc_id . '.jpg');
            }

            // Update database with image paths
            DB::table('testfabrics_subcategory')
                ->where('subcategory__ID', $max_id)
                ->update([
                    'subcategory__image' => 'subcat_images/' . $enc_id . '.jpg',
                ]);

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error adding subcategory: " . $e->getMessage());
        }
    }


  // Get Subcategory by ID
    public function getSubCategoryById($id){
        try {
            $result = DB::select("SELECT * FROM testfabrics_subcategory WHERE subcategory__ID = ?", [$id]);

            if (empty($result)) {
                return null;
            }

            $subcategory = $result[0];
            $enc_id = suCrypt($id);

            // Get the image path for the subcategory
            $subcategoryImagePath = public_path("subcat_images/{$enc_id}.jpg");
            $subcategory->subcategory__image = file_exists($subcategoryImagePath) ? "/subcat_images/{$enc_id}.jpg" : null;

            // Get the banner image path for the subcategory
            $bannerImagePath = public_path("subcat_images/banner_{$enc_id}.jpg");
            $subcategory->banner__image = file_exists($bannerImagePath) ? "/subcat_images/banner_{$enc_id}.jpg" : null;

            return $subcategory;
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    // Update Subcategory
    public function updateSubCategory($id, $data, $subcategoryImage = null, $bannerImage = null)
    {
        try {
            $query = "
                UPDATE testfabrics_subcategory 
                SET subcategory__Name = :name,
                    subcategory__Category_Name = :category_name,
                    subcategory__Sequence = :sequence,
                    subcategory__Status = :status,
                    isEquipment = :equipment
                WHERE subcategory__ID = :id
            ";

            DB::update($query, [
                'name' => $data['subcategory__Name'],
                'category_name' => $data['subcategory__Category_Name'],
                'sequence' => $data['subcategory__Sequence'],
                'status' => $data['subcategory__Status'],
                'equipment' => $data['isEquipment'],
                'id' => $id,
            ]);

            $enc_id = suCrypt($id);

            // Handle the subcategory image if provided
            if ($subcategoryImage) {
                // Define the new field name for the image
                $imageFieldName = 'subcategory__image';

                // Get the old image path
                $oldSubcategoryImagePath = public_path("subcat_images/{$enc_id}.jpg");

                // Check if the old image exists and delete it
                if (file_exists($oldSubcategoryImagePath)) {
                    unlink($oldSubcategoryImagePath);
                }

                // Move the new image to the appropriate directory
                $subcategoryImage->move(public_path('subcat_images'), "{$enc_id}.jpg");

                // Update the database field to reflect the new image
                $updateQuery = "UPDATE testfabrics_subcategory SET {$imageFieldName} = :image_path WHERE subcategory__ID = :id";
                DB::update($updateQuery, [
                    'image_path' => "{$enc_id}.jpg",
                    'id' => $id,
                ]);
            }


            // Handle the banner image if provided
            if ($bannerImage) {
                $oldBannerImagePath = public_path("subcat_images/banner_{$enc_id}.jpg");
                if (file_exists($oldBannerImagePath)) {
                    unlink($oldBannerImagePath);
                }

                $bannerImage->move(public_path('subcat_images'), "banner_{$enc_id}.jpg");
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error updating subcategory: " . $e->getMessage());
        }
    }


    // Delete Subcategory
    public function deleteSubCategory($id){
        try {
            // Delete subcategory record from the database
            $query = "DELETE FROM testfabrics_subcategory WHERE subcategory__ID = :id";
            DB::delete($query, ['id' => $id]);

            // Generate the encrypted ID for image paths
            $enc_id = suCrypt($id);

            // Delete the subcategory image
            $subcategoryImagePath = public_path("subcat_images/{$enc_id}.jpg");
            if (file_exists($subcategoryImagePath)) {
                unlink($subcategoryImagePath);
            }

            // Delete the banner image
            $bannerImagePath = public_path("subcat_images/{$enc_id}_banner.jpg");
            if (file_exists($bannerImagePath)) {
                unlink($bannerImagePath);
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting subcategory: " . $e->getMessage());
        }
    }



}
