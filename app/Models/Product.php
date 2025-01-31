<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

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
                    ->addColumn('action', function($row) {
                        // You can generate the links dynamically, assuming you have the data available
                        // $viewLink = route('survey.view', ['id' => $row->id]);
                        // $editLink = route('survey.edit', ['id' => $row->id]);
                        $editLink = route('product.edit', $row->product__ID);
                        $deleteLink = route('product.destroy', $row->product__ID);
                        $manageFileLink = route('upload-related-document.create', $row->product__ID);
                        $addStandardLink = route('product.standard.index', $row->product__ID);
                        
                        // Returning the HTML for the action column
                        return '
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="' . $editLink . '"><i class="bx bx-edit-alt me-2"></i> Edit</a>
                                    <a class="dropdown-item" href="' . $deleteLink . '"><i class="bx bx-trash me-2"></i> Delete</a>
                                    <a class="dropdown-item" href="' . $manageFileLink . '"><i class="bx bx-file-blank me-2"></i> Manage file</a>  
                                    <a class="dropdown-item" href="' . $addStandardLink . '"><i class="bx bx-list-plus me-2"></i> Add Standard</a>  
                                </div>
                            </div>
                        ';
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

    public function getStandard(){
        try {
            $standards = DB::table('testfabrics_standards')
                            ->select("standards__ID as id","standards__Name as name")
                            ->get();
            return $standards;
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function getProductByStandardForDataTable($id){
        try {

            $query = DB::table('testfabrics_pro_standard_method as tpsm')
                ->join('testfabrics_standards as ts', 'tpsm.pro__Standard_id', '=', 'ts.standards__ID')
                ->join('testfabrics_methods as tm', 'tpsm.pro__Method_id', '=', 'tm.methods__ID')
                ->select(
                    'tpsm.*', 
                    'ts.standards__ID',
                    'ts.standards__Name',
                    'tm.methods__Name',
                    'tm.methods__ID'
                )
                ->where('tpsm.pro__Product_id', $id);
    
            return DataTables::of($query)
                ->order(function ($query) {
                    $query->orderBy('ts.standards__Name', 'asc');
                })
                ->addColumn('action', function ($row) use ($id) {
                    $delete_link = route('product.standard.destroy', $row->pro__ID);
                    $edit_link = '';

                    return view('components.action-button', compact('delete_link','edit_link'))->render();
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    // This method saves the product standard methods
    public function saveProductStandardMethods($productId, $standardId){
        // Get all methods related to the provided standard_id
        $methods = DB::table('testfabrics_methods')
            ->where('methods__Standard_id', $standardId)
            ->pluck('methods__ID');

        // Prepare the data to be inserted into testfabrics_pro_standard_method
        $data = [];
        foreach ($methods as $methodId) {
            $data[] = [
                'pro__Product_id' => $productId,
                'pro__Standard_id' => $standardId,
                'pro__Method_id' => $methodId,
            ];
        }

        // Insert the methods for the product into testfabrics_pro_standard_method
        if (!empty($data)) {
            DB::table('testfabrics_pro_standard_method')->insert($data);
        } else {
            throw new \Exception('No methods found for this standard');
        }
    }
    
    public function deleteProductStandard($id){
        try {
            $result = DB::delete("DELETE FROM testfabrics_pro_standard_method WHERE pro__ID = ?",[$id]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getSubcategory(){
        try {
            $subcategory = DB::table("testfabrics_subcategory")
                                    ->select("subcategory__ID as id","subcategory__Name as name")
                                    ->get();
            return $subcategory;

        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public static function convertXLStoCSV($inputFile, $outputFile){
        $spreadsheet = IOFactory::load($inputFile);
        $writer = IOFactory::createWriter($spreadsheet, 'Csv');
        $writer->save($outputFile);
    }
    
    public static function processCsvData($data, $subcategoryName, $images, $index){
        try {
            $minOrder = DB::table('testfabrics_min_quantity')->where('Min__Name', $data[4])->first();
            if (!$minOrder && $data[4]) {
                $minOrderId = DB::table('testfabrics_min_quantity')->insertGetId([
                    'Min__Name' => $data[4],
                    'Min__Status' => 'Show',
                    'excel' => 1,
                ]);
            } else {
                $minOrderId = $minOrder->Min__ID ?? null;
            }
    
            $available = DB::table('testfabrics_available_in')->where('Available__Name', $data[3])->first();
            if (!$available && $data[3]) {
                $availableId = DB::table('testfabrics_available_in')->insertGetId([
                    'Available__Name' => $data[3],
                    'Available__Status' => 'Show',
                    'excel' => 1,
                ]);
            } else {
                $availableId = $available->Available__ID ?? null;
            }
    
            $productNumber = DB::table('testfabrics_product')->where('product__Number', $data[0])->value('product__Number');
    
            if (!$productNumber && $data[0]) {
                // Insert new product with default values
                $productId = DB::table('testfabrics_product')->insertGetId([
                    'product__Number' => $data[0],
                    'product__Name' => $data[1],
                    'product__Category_Name' => $subcategoryName[1],
                    'product__Subcategory_Name' => $subcategoryName[0],
                    'product__Available' => $availableId,
                    'product__Description' => $data[2],
                    'product__MOQ' => $minOrderId,
                    'show_product' => 1,
                    'show_on_app' => 0,
                    'isEquipment' => 0,
                    'show_on_home' => 0,
                    'excel' => 1,
                    'best_seller' => 0,
                    'product__Weight_gm_m2' => $data[5],
                    'product__Weight_Oz_yd2' => $data[6],
                    'product__Width_Cm' => $data[7],
                    'product__Width_Inches' => $data[8],
                ]);
            } else {
                // Update only relevant fields, leaving show_product, show_on_app, etc., unchanged
                DB::table('testfabrics_product')->where('product__Number', $data[0])->update([
                    'product__Name' => $data[1],
                    'product__Category_Name' => $subcategoryName[1],
                    'product__Subcategory_Name' => $subcategoryName[0],
                    'product__MOQ' => $minOrderId,
                    'product__Available' => $availableId,
                    'product__Description' => $data[2],
                    'product__Weight_gm_m2' => $data[5],
                    'product__Weight_Oz_yd2' => $data[6],
                    'product__Width_Cm' => $data[7],
                    'product__Width_Inches' => $data[8],
                ]);
            }
        } catch (\Exception $e) {
            throw new \Exception('Error processing CSV: '.$e->getMessage());
        }
    }
    

    // export product data
    public function exportToExcel($subcategoryID) {
        try {
            // Fetch subcategory name
            $subcat = DB::table('testfabrics_subcategory')
                ->where('subcategory__ID', $subcategoryID)
                ->first();

            if (!$subcat) {
                throw new \Exception("Subcategory not found");
            }

            $subcat_name = $subcat->subcategory__Name;
            $subcat_name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $subcat_name);
            // Fetch products
            $products = DB::table('testfabrics_product')
                ->where('product__Subcategory_Name', $subcategoryID)
                ->orderBy('product__Number', 'asc')
                ->get();

            // Create Excel file
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set column headers
            $headers = [
                'ITEM NUMBER', 'NAME', 'DESCRIPTION', 'Available In', 'Min Order',
                'Weight Grams/m²', 'Weight Ounces/yard²', 'Width Cm', 'Width Inches',
                'Image 1', 'Image 2', 'Image 3', 'Image 4', 'Product URL'
            ];

            $columnLetter = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($columnLetter . '1', $header);
                $columnLetter++;
            }

            // Insert data into sheet
            $rowNumber = 2;
            foreach ($products as $product) {
                $available = DB::table('testfabrics_available_in')
                    ->where('Available__ID', $product->product__Available)
                    ->value('Available__Name');

                $minOrder = DB::table('testfabrics_min_quantity')
                    ->where('Min__ID', $product->product__MOQ)
                    ->value('Min__Name');

                $sheet->setCellValueExplicit('A' . $rowNumber, $product->product__Number,  DataType::TYPE_STRING);
                $sheet->setCellValue('B' . $rowNumber, $product->product__Name);
                $sheet->setCellValue('C' . $rowNumber, $product->product__Description);
                $sheet->setCellValue('D' . $rowNumber, $available ?? '');
                $sheet->setCellValue('E' . $rowNumber, $minOrder ?? '');
                $sheet->setCellValue('F' . $rowNumber, $product->product__Weight_gm_m2);
                $sheet->setCellValue('G' . $rowNumber, $product->product__Weight_Oz_yd2);
                $sheet->setCellValue('H' . $rowNumber, $product->product__Width_Cm);
                $sheet->setCellValue('I' . $rowNumber, $product->product__Width_Inches);

                // Product Image Paths
                $imageColumns = ['J', 'K', 'L', 'M'];
                $imageSuffixes = ['', '_a', '_b', '_c'];
                $imagePathBase = public_path("product_images/" . suCrypt($product->product__ID));
                
                foreach ($imageColumns as $index => $column) {
                    $imagePath = $imagePathBase . $imageSuffixes[$index] . ".jpg";
                    if (file_exists($imagePath)) {
                        // Create a new drawing object
                        $drawing = new Drawing();
                        $drawing->setName('Product Image');
                        $drawing->setDescription('Product Image');
                        $drawing->setPath($imagePath); // Set the path to the image
                        $drawing->setHeight(100); // Set the height of the image in pixels (optional)
                        $drawing->setCoordinates($column . $rowNumber); // Set the position where the image will be inserted
                        $drawing->setWorksheet($sheet); // Add the drawing (image) to the worksheet
                    }
                }

                // Product URL
                $sheet->setCellValue('N' . $rowNumber, "http://testfabrics.com/product-detail.php?id=" . suCrypt($product->product__ID) . "&pid=" . suCrypt($subcategoryID));

                $rowNumber++;
            }

            // Adjust column widths to fit content
            foreach (range('A', 'N') as $columnLetter) {
                $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
            }
            // Ensure the directory exists
            $directory = public_path('excel_export');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true); // Create the directory if it doesn't exist
            }

            // Save file
            $fileName = 'excel_export/' . $subcat_name . '.xlsx';
            $writer = new Xlsx($spreadsheet);
            $writer->save($fileName);

            return $fileName;
        } catch (\Exception $e) {
            throw new \Exception("Error exporting data: " . $e->getMessage());
        }
    }


}
