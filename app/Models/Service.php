<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class Service extends Model
{
    public function getServices(){
        try {
            $services =   DB::select("
                SELECT *
                FROM testfabtics_services
                WHERE cat_name='services'
                ORDER BY  associations_and_partners__Name
            ");
            return $services;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getServiceByID($id){
        try {
            $service = DB::select("
                Select * 
                FROM testfabtics_services 
                WHERE associations_and_partners__ID = ? AND cat_name='services'",
                [$id]
            );
            return !empty($service) ? $service[0] : null;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function getServicesForDataTable(){
        try {
            $services = $this->getServices();
            return DataTables::of($services)
                                ->addIndexColumn()
                                ->addColumn('action', function($row) {
                                    $edit_link = route('service.edit', $row->associations_and_partners__ID); 
                                    $delete_link = route('service.destroy', $row->associations_and_partners__ID);
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

    public function saveService($data, $image){
        try {
            $result = DB::insert(
                "INSERT INTO testfabtics_services 
                    (associations_and_partners__Name, associations_and_partners__Abbriviation, 
                     associations_and_partners__Url, associations_and_partners__Description, cat_name) 
                 VALUES 
                    (:name, :abbreviation, :url, :description, :category)",
                [
                    'name' => $data["associations_and_partners__Name"],
                    'abbreviation' => $data["associations_and_partners__Abbriviation"] ?? "",
                    'url' => $data["associations_and_partners__Url"] ?? "",
                    'description' => $data["associations_and_partners__Description"],
                    'category' => 'services'
                ]
            );


            // Get the last inserted ID
            $max_id = DB::getPdo()->lastInsertId();

            // Upload the image using the helper function
            if ($image) {
                $folderName = 'services_images';
                $fileName = imageUpload($folderName, $image, $max_id); // Helper function
                if (!$fileName) {
                    throw new \Exception("Image upload failed.");
                }
            }

            return true;

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateService($data, $id){
        try {
            // Prepare the SQL update query
            $sql = "UPDATE testfabtics_services SET
                        associations_and_partners__Name = :name,
                        associations_and_partners__Abbriviation = :abbreviation,
                        associations_and_partners__Url = :url,
                        associations_and_partners__Description = :description
                    WHERE associations_and_partners__ID = :id  AND cat_name='services'";

            // Execute the update query
            $result = DB::update($sql, [
                'name' => $data["associations_and_partners__Name"],
                'abbreviation' => $data["associations_and_partners__Abbriviation"] ?? "",
                'url' => $data["associations_and_partners__Url"] ?? "",
                'description' => $data["associations_and_partners__Description"],
                'id' => $id,
            ]);

            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function deleteService($id){
        try {
            $result = DB::delete("DELETE FROM testfabtics_services 
                            WHERE associations_and_partners__ID = :id AND cat_name='services'", 
                            ['id' => $id,]);
            return $result;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
