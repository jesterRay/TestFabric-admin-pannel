<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UploadRelatedDocument;

class UploadRelatedDocumentController extends Controller
{
    
    public function index(Request $request){
        try {
            if ($request->ajax()) {
                return (new UploadRelatedDocument)->getFilesForDataTable();
            }
            return view('admin.upload-related-document.index');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function create($id = null){
        try {
            $products = (new UploadRelatedDocument)->getProduct();
            $selectedProduct = null;
            if($id){
                $selectedProduct = (new UploadRelatedDocument)->getProductById($id);
            }

            return view('admin.upload-related-document.create')
                        ->with([
                            "products" => $products,
                            "selectedProduct" => $selectedProduct
                        ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

    }

    public function save(Request $request){
        try {
            $validated = $request->validate([
                'files__Product' => 'required|string',
                'files__Description' => 'nullable|string',
                'file' => 'required|file|max:10240|mimes:pdf,ppt,xlsx,doc,docx,jpg,jpeg,txt',
            ]);

            (new UploadRelatedDocument)->addFile($validated, $request->file('file'));

            return redirect()->route('upload-related-document.index')
                ->with(['success' => 'File uploaded successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => $e->getMessage()])
                ->withInput();
        }
    }


    public function destroy($id){
        try {
            (new UploadRelatedDocument)->deleteFile($id);
            

            return redirect()->route('upload-related-document.index')
                ->with(['success' => 'File deleted successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['error' => $e->getMessage()]);
        }
    }

}
