<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    
    // Show Product List
    public function index(Request $request){
        try {
            if ($request->ajax()) {
                $products = (new Product)->getProductsForDataTable();
                return $products;
            }
            return view('admin.product.index');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    // Show Create Form
    public function create(){
        try {
            $options = (new Product)->getSelectOptionForCRUD();
            return view('admin.product.create')->with([
                'categories' => $options->categories,
                'subcategories' => $options->subcategories,
                'availableIn' => $options->availableIn,
                'minimumQuantity' => $options->minimumQuantity
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    // Save Product
    public function save(Request $request){
        try {
            // Validate the request
            $validated = $request->validate([
                'product__Number' => 'required|numeric',
                'product__Name' => 'required|string',
                'product__Category' => 'nullable|string',
                'product__Description' => 'nullable|string',
                'show_on_home' => 'required|numeric|in:0,1',
                'image_description' => 'nullable|string',
                'product__Category_Name' => 'required|numeric',
                'product__Subcategory_Name' => 'required|numeric',
                'product__Section_No' => 'required|numeric',
                'isEquipment' => 'required|numeric|in:0,1',
                'product__Weight_gm_m2' => 'nullable|numeric',
                'product__Weight_Oz_yd2' => 'nullable|numeric',
                'product__Width_Cm' => 'nullable|numeric',
                'product__Width_Inches' => 'nullable|numeric',
                'product__Available' => 'nullable|numeric',
                'product__MOQ' => 'nullable|numeric',
                'show_product' => 'required|numeric|in:0,1',
                'show_on_app' => 'required|numeric|in:0,1',
                'excel' => 'nullable|numeric|in:0,1',
                'best_seller' => 'nullable|numeric|in:0,1',
                'product__Name_Old' => 'nullable|string',
                'product__Price' => 'nullable|string|max:100',
                'img' => 'nullable|image|mimes:jpg,|max:2048', // Validate image
                'img_a' => 'nullable|image|mimes:jpg,|max:2048', // Validate image
                'img_b' => 'nullable|image|mimes:jpg,|max:2048', // Validate image
                'img_c' => 'nullable|image|mimes:jpg,|max:2048', // Validate image
            ]);

            // Call model function to save the data
            $result = (new Product)
                                ->addProduct(
                                    $validated, 
                                    $request->file('img'),
                                    $request->file('img_a'),
                                    $request->file('img_b'),
                                    $request->file('img_c')
                                );

            if ($result) {
                return redirect()->route('product.index')->with(['success' => 'Product added successfully.']);
            }

            return redirect()->back()->with(['error' => 'There was an error adding the product.'])->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }

    // Edit Product
    public function edit($id){
        try {
            // Fetch the product data by ID
            $product = (new Product)->getProductById($id);
            $options = (new Product)->getSelectOptionForCRUD();

            if (!$product) {
                return redirect()->back()->with(['error' => 'Product not found.']);
            }

            return view('admin.product.edit')
                ->with([
                    'product' => $product,
                    'categories' => $options->categories,
                    'subcategories' => $options->subcategories,
                    'availableIn' => $options->availableIn,
                    'minimumQuantity' => $options->minimumQuantity,
                ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    // Update Product
    public function update(Request $request, $id){
        try {

            // Validate the request
            $validated = $request->validate([
                'product__Number' => 'required|numeric',
                'product__Name' => 'required|string',
                'product__Description' => 'nullable|string',
                'show_on_home' => 'required|numeric|in:0,1',
                'image_description' => 'nullable|string',
                'product__Category_Name' => 'required|numeric',
                'product__Subcategory_Name' => 'required|numeric',
                'product__Section_No' => 'required|numeric',
                'isEquipment' => 'required|numeric|in:0,1',
                'product__Weight_gm_m2' => 'nullable|numeric',
                'product__Weight_Oz_yd2' => 'nullable|numeric',
                'product__Width_Cm' => 'nullable|numeric',
                'product__Width_Inches' => 'nullable|numeric',
                'product__Available' => 'nullable|numeric',
                'product__MOQ' => 'nullable|numeric',
                'show_product' => 'required|numeric|in:0,1',
                'show_on_app' => 'required|numeric|in:0,1',
                'img' => 'nullable|image|mimes:jpg,|max:2048', // Validate image
                'img_a' => 'nullable|image|mimes:jpg,|max:2048', // Validate image
                'img_b' => 'nullable|image|mimes:jpg,|max:2048', // Validate image
                'img_c' => 'nullable|image|mimes:jpg,|max:2048', // Validate image
            ]);

            // Call model function to save the data
            $result = (new Product)
                                ->updateProduct(
                                    $id,
                                    $validated, 
                                    $request->file('img'),
                                    $request->file('img_a'),
                                    $request->file('img_b'),
                                    $request->file('img_c')
                                );

            if ($result) {
                return redirect()->route('product.index')->with(['success' => 'Product updated successfully.']);
            }

            return redirect()->back()->with(['error' => 'There was an error updating the product.'])->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }

    // Delete Product
    public function destroy($id){
        try {
            // Call the model to delete the product
            $result = (new Product)->deleteProduct($id);

            if ($result) {
                return redirect()->route('product.index')->with(['success' => 'Product deleted successfully.']);
            }

            return redirect()->back()->with(['error' => 'Product could not be deleted.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function subcategoryDelete(){
        try {
            $options = (new Product)->getSelectOptionForCRUD();
            return view('admin.product.delete-by-subcategory')->with([
                'subcategories' => $options->subcategories
            ]);
            
        } catch (\Throwable $th) {
            return redirect()->back()->with(['error' => $th->getMessage()]);
        }
    }

    public function subcategoryDestroy(Request $request){
        try {
            $request->validate([
                'subcategory' => 'required|numeric'
            ]);
            $result = (new Product)->deleteBySubCategory($request->subcategory);
            return redirect()->back()->with("success","Deleted Successfully");
        } catch (\Throwable $th) {
            return redirect()->back()->with(['error' => $th->getMessage()]);
        }
    }

    // to show the index product standard
    public function productStandard(Request $request, $id){
        try {

            if($request->ajax()){
                $standard = (new Product)->getProductByStandardForDataTable($id);
                return $standard;
            }

            $standardOptions = (new Product)->getStandard();
            return view('admin.product.product-standard')->with([
                'product_id' => $id,
                "standardOptions" => $standardOptions
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with(['error' => $th->getMessage()]);
        }
    }
    
    public function productStandardSave($id, Request $request){
        // Validate the incoming request
        $request->validate([
            'standard_id' => 'required|integer',  // Ensure standard_id is an integer
        ]);

        $standardId = $request->input('standard_id');
        
        try {
            // Pass the product ID and standard ID to the model method
            (new Product)->saveProductStandardMethods($id, $standardId);

            return redirect()->back()->with(['success' => 'Product assigned to standard successfully!']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    // to delete the existing product standard
    public function productStandardDestroy($id){
        try {
            $result = (new Product)->deleteProductStandard($id);
            return redirect()->back()->with('success','Product standard deleted successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with(['error' => $th->getMessage()]);
        }
    }

    public function importProduct(){
        try {
            $subcategories = (new Product)->getSubcategory();
            return view('admin.product.import')->with([
                "subcategories" => $subcategories
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Error: '.$e->getMessage());
            
        }
    }
    // import product data
    public function uploadProductData(Request $request){
        try {
            $request->validate([
                'ufile' => 'required|mimes:xlsx|max:2048',
                'product__Subcategory_Name' => 'required|numeric',
            ]);
            
            $file = $request->file('ufile');
            $filename = time().'_'.$file->getClientOriginalName();
            $filePath = public_path('csv/'.$filename);
            $file->move(public_path('csv'), $filename);
            
            $newCsvFile = public_path('csv/'.rand().'.csv');
            Product::convertXLStoCSV($filePath, $newCsvFile);
            
            $spreadsheet = IOFactory::load($filePath);
            $images = [];
            
            foreach ($spreadsheet->getActiveSheet()->getDrawingCollection() as $drawing) {
                if ($drawing instanceof \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing) {
                    ob_start();
                    call_user_func($drawing->getRenderingFunction(), $drawing->getImageResource());
                    $imageContents = ob_get_contents();
                    ob_end_clean();
                    $extension = 'png';
                } else {
                    $zipReader = fopen($drawing->getPath(), 'r');
                    $imageContents = '';
                    while (!feof($zipReader)) {
                        $imageContents .= fread($zipReader, 1024);
                    }
                    fclose($zipReader);
                    $extension = $drawing->getExtension();
                }
                
                $cellId = $drawing->getCoordinates();
                $imagePath = public_path('product_images/'.$cellId.'.jpg');
                file_put_contents($imagePath, $imageContents);
                $images[$cellId] = $imagePath;
            }
            
            $handle = fopen($newCsvFile, 'r');
            $i = 0;
            while (($data = fgetcsv($handle, 4096, ",")) !== false) {
                if ($i > 0) {
                    Product::processCsvData($data, $request->input('product__Subcategory_Name'), $images, $i);
                }
                $i++;
            }
            fclose($handle);
            unlink($filePath);
            unlink($newCsvFile);
            
            return redirect()->route('upload_product_data')->with('success', 'File uploaded successfully!');
        } catch (\Exception $e) {
            return redirect()->route('upload_product_data')->with('error', 'Error: '.$e->getMessage());
        }
    }

    public function exportProduct(){
        try {
            $subcategories = (new Product)->getSubcategory();
            return view('admin.product.export')->with([
                "subcategories" => $subcategories
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Error: '.$e->getMessage());
            
        }
    }

    // export product data
    public function exportProductData(Request $request){
        $validator = $request->validate([
            'product_Subcategory_Name' => 'required|numeric',
        ]);

        

        try {
            $filePath = (new Product)->exportToExcel($request->input('product_Subcategory_Name'));

            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate file: ' . $e->getMessage()], 500);
        }
    }


}
