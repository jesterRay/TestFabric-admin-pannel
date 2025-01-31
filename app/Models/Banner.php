<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class Banner extends Model
{


    public function getBanner(){
        try {
            $banners = DB::select("SELECT * FROM testfabrics_banners ORDER BY files__Name");
            return $banners;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // get banners and format it into DataTable format 
    public function getBannerForDataTable(){
        try {
            $query = DB::table('testfabrics_banners');          
            return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('product_image', function ($row) {
                // Assuming the image path is stored in $row->files__picture
                $imageFileName = suCrypt($row->files__ID) . '.' . $row->files__Ext;
                $imagePath = asset('bannerfiles/' . $imageFileName);
                return '<a href="' . $imagePath . '" target="_blank">
                            <img src="' . $imagePath . '" alt="Banner Image" style="width: 50px; height: 50px; object-fit: cover;">
                        </a>';
            })
            ->addColumn('action', function($row) {
                $edit_link = '';
                $delete_link = route('banner.destroy', $row->files__ID);
                $view_link = '';
                return view('components.action-button', 
                            compact('edit_link', 'delete_link', 'view_link'))
                            ->render();
            })
            ->rawColumns(['product_image','action'])
            ->make(true);

        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function createBanner($data){
        try {
            // Extract file information
            $file = $data['files__file'];
            $nameWithoutExt = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $prdExt = $extension; // Assuming 'files__picture' uses the same extension as the uploaded file.

            // Start a database transaction
            DB::beginTransaction();

            // Insert the record and get the ID
            $bannerId = DB::table('testfabrics_banners')->insertGetId([
                'files__Name' => $nameWithoutExt,
                'files__Ext' => $extension,
                'files__picture' => $prdExt,
                'files__Link' => $data['files__Link'] ?? '',
                'files__download_name' => $data['files__download'] ?? '',
                'files__Description' => $data['files__Description'] ?? '',
            ]);

            // Use the custom helper to upload the file
            $filePath = imageUpload('bannerfiles', $file, $bannerId);

            // Commit the transaction
            DB::commit();

            return $bannerId; // Return the ID of the created banner
        } catch (Exception $e) {
            // Rollback on failure and throw the exception
            DB::rollBack();
            throw new Exception("Failed to create banner: " . $e->getMessage());
        }
    }

    
    public static function deleteBanner($id){
        try {

            // Delete the record from the database
            $query = "DELETE FROM testfabrics_banners WHERE files__ID = :id";
            $result = DB::delete($query, ['id' => $id]);

            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }


            // Retrieve the file path for the associated image
            $folderName = 'bannerfiles';

            // helper function to delete the image
            deleteImage($folderName,$id);

            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

}
