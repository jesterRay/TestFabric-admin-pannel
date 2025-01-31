<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; 
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;


class TPVS extends Model
{
    public function getTPVSContent(){
        try {
            $content = DB::select("SELECT * FROM testfabrics_tpvs_content");
            return $content ? $content[0] : null;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function updateTPVSContent($id, $data, $image = null){
        try {
            // Get existing image path
            $existingImagePath = DB::table('testfabrics_tpvs_content')
                ->where('tpvs__ID', $id)
                ->value('image');
    
            // Handle the image if provided
            if ($image) {
                // Validate the image
                if (!$image->isValid()) {
                    throw new \Exception("Invalid image file.");
                }
    
                $extension = $image->getClientOriginalExtension();
                $fileName = 'tpvs_' . time() . '.' . $extension;
                $folderName = "upload_header";
    
                // Store the full image path
                $imagePath = "$folderName/$fileName"; 
    
                // Define the upload path (using public_path directly)
                $uploadPath = public_path($folderName); 
    
                // Ensure the folder exists
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
    
                $image->move($uploadPath, $fileName);
    
                // Delete existing image if it exists
                if ($existingImagePath) {
                    // Use File::delete() for cleaner deletion
                    File::delete(public_path($existingImagePath)); 
                }
            } else {
                $imagePath = $existingImagePath; 
            }
    
            // Use DB::table() for updating
            DB::table('testfabrics_tpvs_content')
                ->where('tpvs__ID', $id)
                ->update([
                    'heading_1' => $data['heading_1'],
                    'heading_2' => $data['heading_2'],
                    'pink_message' => $data['pink_message'],
                    'yellow_message' => $data['yellow_message'],
                    'green_message' => $data['green_message'],
                    'image' => $imagePath, 
                ]);
    
            return true;
    
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getTPVSInfo(){
        try {
            $data = DB::select("SELECT * FROM testfabrics_tpvs_data ORDER BY tpvs__serial");
            return $data;
        } catch (\Throwable $th) {
            throw $th;
        }

    }
    
    // get Standard and format it into DataTable format 
    public function getTPVSInfoForDataTable(){
        try {
            $query = DB::table('testfabrics_tpvs_data');
            return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function insertSerialRange($start, $end){
        try {
            for ($i = $start; $i <= $end; $i++) {
                // Check if record exists
                $exists = DB::table('testfabrics_tpvs')
                    ->where('tpvs__serial', $i)
                    ->exists();

                if (!$exists) {
                    DB::insert('
                        INSERT INTO 
                        testfabrics_tpvs (tpvs__serial, date_added, status, checked, by_csv) 
                        VALUES (?, NOW(), ?, ?, ?)', [
                        $i, 
                        1, 
                        0,
                        0
                    ]);
                }
            }
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getSerial(){
        try {
            $serials = DB::select("SELECT * FROM testfabrics_tpvs ORDER BY tpvs__serial");
            return $serials;
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function getSerialForDataTable($request){
        try {
            $query = DB::table('testfabrics_tpvs');

            return DataTables::of($query)
                ->addColumn('is_checked', function($row) {
                    return '<input 
                                type="checkbox" 
                                class="row-check" 
                                data-id="' . $row->tpvs__ID . '" 
                                ' . ($row->checked ? 'checked' : '') . ' 
                                onclick="handleRowCheck(this)">';
                })
                ->rawColumns(['is_checked'])
                ->addIndexColumn()
                ->make(true);


        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function updateCheckedStatus($id,$checked){
        try {

            $updated = DB::table('testfabrics_tpvs')
            ->where('tpvs__ID', $id)
            ->update(['checked' => $checked]);
            
            return $updated > 0;

        } catch (\Throwable $th) {
            throw $th;
        }
    }


}
