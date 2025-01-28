<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class Product extends Model
{
    // Fetch Categories
    public function getCategories(){
        try {
            $categories = DB::table('testfabrics_category')
                            ->select("category__ID as id", "category__Name as name")
                            ->get();
            return $categories;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // Get Products for DataTable
    public function getProductsForDataTable(){
        try {
            $query = DB::table('testfabrics_product')
                        ->join('testfabrics_category',
                                'testfabrics_product.product__Category_Name',
                                '=',
                                'testfabrics_category.category__ID')
                        ->join('testfabrics_subcategory',
                                'testfabrics_product.product__Subcategory_Name', '=', 
                                'testfabrics_subcategory.subcategory__ID')
                        ->select(
                            'testfabrics_product.*',
                            'testfabrics_category.category__Name as category_name', // Aliasing category name
                            'testfabrics_subcategory.subcategory__Name as subcategory_name' // Aliasing subcategory name
                        );

            return DataTables::of($query)
                    ->order(function ($query) {
                        $query->orderBy('product__Name', 'asc');
                    })
                    ->addColumn('action', function ($row) {
                        $edit_link = route('product.edit', $row->product__ID);
                        $delete_link = route('product.destroy', $row->product__ID);
                        return view('components.action-button', compact('edit_link', 'delete_link'))->render();
                    })
                    ->rawColumns(['action'])
                    ->addIndexColumn()
                    ->make(true);
        } catch (\Throwable $th) {
            throw $th;
        }
    }



    public function addProduct($data, $img = null, $img_a = null, $img_b = null, $img_c = null){
        try {
            $query = "
                INSERT INTO testfabrics_product (
                    product__Number,
                    product__Name,
                    product__Category,
                    product__Description,
                    show_on_home,
                    image_description,
                    product__Category_Name,
                    product__Subcategory_Name,
                    product__Section_No,
                    isEquipment,
                    product__Weight_gm_m2,
                    product__Weight_Oz_yd2,
                    product__Width_Cm,
                    product__Width_Inches,
                    product__Available,
                    product__MOQ,
                    show_product,
                    show_on_app,
                    excel,
                    best_seller,
                    product__Name_Old,
                    product__Price
                ) VALUES (
                    :number,
                    :name,
                    :category,
                    :description,
                    :show_on_home,
                    :image_description,
                    :category_name,
                    :subcategory_name,
                    :section_no,
                    :is_equipment,
                    :weight_gm,
                    :weight_oz,
                    :width_cm,
                    :width_inches,
                    :available,
                    :min_quantity,
                    :show_product,
                    :show_on_app,
                    :excel,
                    :best_seller,
                    :old_name,
                    :price
                )
            ";
    
            DB::insert($query, [
                'number' => $data['product__Number'],
                'name' => $data['product__Name'],
                'category' => $data['product__Category'] ?? "",
                'description' => $data['product__Description'] ?? "",
                'show_on_home' => $data['show_on_home'],
                'image_description' => $data['image_description'] ?? "",
                'category_name' => $data['product__Category_Name'],
                'subcategory_name' => $data['product__Subcategory_Name'],
                'section_no' => $data['product__Section_No'],
                'is_equipment' => $data['isEquipment'],
                'weight_gm' => $data['product__Weight_gm_m2'] ?? "",
                'weight_oz' => $data['product__Weight_Oz_yd2'] ?? "",
                'width_cm' => $data['product__Width_Cm'] ?? "",
                'width_inches' => $data['product__Width_Inches'] ?? "",
                'available' => $data['product__Available'] ?? "",
                'min_quantity' => $data['product__MOQ'] ?? "",
                'show_product' => $data['show_product'],
                'show_on_app' => $data['show_on_app'],
                'excel' => $data['excel'] ?? 0,
                'best_seller' => $data['best_seller'] ?? 0,
                'old_name' => $data['product__Name_Old'] ?? '',
                'price' => $data['product__Price'] ?? ''
            ]);
    
            $id = DB::getPdo()->lastInsertId();
            $enc_id = suCrypt($id);
    
            // Handle main image
            if ($img) {
                $img->move(public_path('product_images'), $enc_id . '.jpg');
            }
    
            // Handle additional images
            if ($img_a) {
                $img_a->move(public_path('product_images'), $enc_id . '_a.jpg');
            }
    
            if ($img_b) {
                $img_b->move(public_path('product_images'), $enc_id . '_b.jpg');
            }
    
            if ($img_c) {
                $img_c->move(public_path('product_images'), $enc_id . '_c.jpg');
            }
    
            return true;
        } catch (\Exception $e) {
            \Log::error('Error adding product: ' . $e->getMessage());
            throw new \Exception("Error adding product: " . $e->getMessage());
        }
    }

    public function getProductById($id){
        try {
                // Get product data from database
                $result = DB::select("SELECT * FROM testfabrics_product WHERE product__ID = ?", [$id]);

                if (empty($result)) {
                    return null;
                }

                $product = $result[0];
                $enc_id = suCrypt($id);

                // Add image paths to product object with existence check
                $mainImage = public_path('product_images/' . $enc_id . '.jpg');
                $product->img = file_exists($mainImage) ? 'product_images/' . $enc_id . '.jpg' : null;

                $imageA = public_path('product_images/' . $enc_id . '_a.jpg');
                $product->img_a = file_exists($imageA) ? 'product_images/' . $enc_id . '_a.jpg' : null;

                $imageB = public_path('product_images/' . $enc_id . '_b.jpg');
                $product->img_b = file_exists($imageB) ? 'product_images/' . $enc_id . '_b.jpg' : null;

                $imageC = public_path('product_images/' . $enc_id . '_c.jpg');
                $product->img_c = file_exists($imageC) ? 'product_images/' . $enc_id . '_c.jpg' : null;

                return $product;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // Update Product
    public function updateProduct($id, $data, $img = null, $img_a = null, $img_b = null, $img_c = null){
        try {
            $query = "
                UPDATE testfabrics_product
                SET 
                    product__Number = :number,
                    product__Name = :name,
                    product__Description = :description,
                    show_on_home = :show_on_home,
                    image_description = :image_description,
                    product__Category_Name = :category_name,
                    product__Subcategory_Name = :subcategory_name,
                    product__Section_No = :section_no,
                    isEquipment = :is_equipment,
                    product__Weight_gm_m2 = :weight_gm,
                    product__Weight_Oz_yd2 = :weight_oz,
                    product__Width_Cm = :width_cm,
                    product__Width_Inches = :width_inches,
                    product__Available = :available,
                    product__MOQ = :min_quantity,
                    show_product = :show_product,
                    show_on_app = :show_on_app
                WHERE product__ID = :id
            ";

            DB::update($query, [
                'id' => $id,
                'number' => $data['product__Number'],
                'name' => $data['product__Name'],
                'description' => $data['product__Description'] ?? "",
                'show_on_home' => $data['show_on_home'],
                'image_description' => $data['image_description'] ?? "",
                'category_name' => $data['product__Category_Name'],
                'subcategory_name' => $data['product__Subcategory_Name'],
                'section_no' => $data['product__Section_No'],
                'is_equipment' => $data['isEquipment'],
                'weight_gm' => $data['product__Weight_gm_m2'] ?? "",
                'weight_oz' => $data['product__Weight_Oz_yd2'] ?? "",
                'width_cm' => $data['product__Width_Cm'] ?? "",
                'width_inches' => $data['product__Width_Inches'] ?? "",
                'available' => $data['product__Available'] ?? "",
                'min_quantity' => $data['product__MOQ'] ?? "",
                'show_product' => $data['show_product'],
                'show_on_app' => $data['show_on_app']
            ]);

            $enc_id = suCrypt($id);

            // Update main image if provided
            if ($img) {
                $img->move(public_path('product_images'), $enc_id . '.jpg');
            }

            // Update additional images if provided
            if ($img_a) {
                $img_a->move(public_path('product_images'), $enc_id . '_a.jpg');
            }

            if ($img_b) {
                $img_b->move(public_path('product_images'), $enc_id . '_b.jpg');
            }

            if ($img_c) {
                $img_c->move(public_path('product_images'), $enc_id . '_c.jpg');
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Error updating product: ' . $e->getMessage());
            throw new \Exception("Error updating product: " . $e->getMessage());
        }
    }

    // Delete Product
    public function deleteProduct($id) {
        try {
            // Delete the product record from database
            $query = "DELETE FROM testfabrics_product WHERE product__ID = :id";
            DB::delete($query, ['id' => $id]);
            

            $enc_id = suCrypt($id);
            // Define all possible image paths
            $imagePaths = [
                public_path("product_images/{$enc_id}.jpg"),  // Main image
                public_path("product_images/{$enc_id}_a.jpg"), // Image A
                public_path("product_images/{$enc_id}_b.jpg"), // Image B
                public_path("product_images/{$enc_id}_c.jpg")  // Image C
            ];
    
            // Delete all images if they exist
            foreach ($imagePaths as $imagePath) {
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
    

    
            return true;
    
        } catch (\Exception $e) {
            throw new \Exception("Error deleting product: " . $e->getMessage());
        }
    }

    public function getSelectOptionForCRUD(){
        try {
            $options = new \stdClass();
            $options->categories = DB::table("testfabrics_category")
                                ->select("category__ID as id", "category__Name as name")
                                ->get();

            $options->subcategories = DB::table("testfabrics_subcategory")
                                    ->select("subcategory__ID as id","subcategory__Name as name")
                                    ->get();

            $options->availableIn = DB::table("testfabrics_available_in")
                                        ->select("Available__ID as id","Available__Name as name")
                                        ->get();
            
            $options->minimumQuantity = DB::table("testfabrics_min_quantity")
                                        ->select("Min__ID as id","Min__Name as name")
                                        ->get();
            
            return $options;

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteBySubCategory($id){
        try {
            $result= DB::delete("
                            DELETE FROM testfabrics_product 
                            WHERE  product__Subcategory_Name = ? ",[$id]);
            return $result;
        } catch (\Throwable $th) {
            throw $th;
        }
    }


}
