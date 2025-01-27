<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class AboutUs extends Model
{

    public function getAboutUs(){
        try {
            $aboutUs =   DB::select("SELECT associations_and_partners__ID as id,
                associations_and_partners__Name as name,
                associations_and_partners__Abbriviation as abbreviation,
                associations_and_partners__Url as url 
                FROM testfabtics_services
                WHERE cat_name='aboutus'
                ORDER BY  associations_and_partners__Name");
            return $aboutUs;

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getAboutUsByID($id){
        try {
            $aboutUs = DB::select(
                        "Select * FROM testfabtics_services
                        WHERE associations_and_partners__ID = ? 
                        AND cat_name='aboutus'",[$id]
                    );
                    
            return !empty($aboutUs) ? $aboutUs[0] : null;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getAboutUsForDataTable(){
        try {
            $aboutUs = $this->getAboutUs();
            return DataTables::of($aboutUs)
                                ->addIndexColumn()
                                ->addColumn('action', function($row) {
                                    $edit_link = route('about-us.edit', $row->id); 
                                    $delete_link = route('about-us.destroy', $row->id);
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

    public function saveAboutUs($name, $description, $abbrevation, $url, $cat_name){
        try {
            $result = DB::insert(
                "INSERT INTO testfabtics_services 
                    (associations_and_partners__Name, associations_and_partners__Abbriviation, 
                    associations_and_partners__Url, associations_and_partners__Description, cat_name) 
                VALUES 
                    (:name, :abbreviation, :url, :description, :category)",
                [
                    'name' => $name,
                    'abbreviation' => $abbrevation,
                    'url' => $url,
                    'description' => $description,
                    'category' => $cat_name
                ]
            );

            return $result;

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateAboutUs($name, $abbrevation, $url, $description, $id){
        try {
            // Prepare the SQL update query
            $sql = "UPDATE testfabtics_services SET
                        associations_and_partners__Name = :name,
                        associations_and_partners__Abbriviation = :abbreviation,
                        associations_and_partners__Url = :url,
                        associations_and_partners__Description = :description
                    WHERE associations_and_partners__ID = :id AND cat_name='aboutus' ";

            // Execute the update query
            $result = DB::update($sql, [
                'name' => $name,
                'abbreviation' => $abbrevation,
                'url' => $url,
                'description' => $description,
                'id' => $id,
            ]);

            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function deleteAboutUs($id){
        try {
            $result = DB::delete("DELETE FROM testfabtics_services 
                            WHERE associations_and_partners__ID = :id AND cat_name='aboutus'", 
                            ['id' => $id,]);
            return $result;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
