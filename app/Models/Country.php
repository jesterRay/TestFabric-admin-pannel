<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class Country extends Model
{

    public function getCountry($id){
        try {
            $countries = DB::select("
                            SELECT * 
                            FROM testfabrics_map_countries 
                            WHERE countries__Map_ID = ? 
                            ORDER BY countries__Name ",[$id]);
            return $countries;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function getCountryById($id){
        try {
            $result = DB::select("SELECT * FROM testfabrics_map_countries where countries__ID = ? ",[$id]);
            
            
            if (empty($result)) {
                return null;
            }

            $country = $result[0];
            

            // Check if the image exists using the findImage function
            $imagePath = findImage($id, 'map_images');

            // Add the image path to the career details, or set it to null if no image found
            $country->imgfile = $imagePath ? $imagePath : null;

            return $country;
    
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    // get Country and format it into DataTable format
    public function getCountryForDataTable($id){
        try {
            $countries = $this->getCountry($id);
            $query = DB::table('testfabrics_map_countries')->where('countries__Map_ID','=',$id);
            return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $edit_link = route('country.edit', $row->countries__ID); // Changed to country
                $delete_link = route('country.destroy', $row->countries__ID); // Changed to country
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

    public function getContinents(){
        try {
            $continents = DB::select("SELECT map__ID as id, map__Name as name FROM testfabrics_map");
            return $continents;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    
    public function getCountriesForSelect(){
        try {
            $countries = DB::select("SELECT countries__ID as id, countries__Name as name FROM testfabrics_map_countries");
            return $countries;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    // add Country and its image
    public function addCountry($data, $image){
        try {

            // Insert the record into the database
            $query = "
                INSERT INTO testfabrics_map_countries 
                (countries__Name, countries__Map_ID, countries__Status)
                VALUES (:name, :continent_id, :status)
            ";

            DB::insert($query, [
                'name' => $data['countries__Name'],
                'continent_id' => $data['countries__Map_ID'] ?? '',
                'status' => $data['countries__Status'] ?? 'Show',
            ]);

            // Get the last inserted ID
            $max_id = DB::getPdo()->lastInsertId();

            // Upload the image using the helper function
            if ($image) {
                $folderName = 'map_images';
                $fileName = imageUpload($folderName, $image, $max_id); // Helper function
                if (!$fileName) {
                    throw new \Exception("Image upload failed.");
                }
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error adding Standard Image: " . $e->getMessage());
        }
    }   
    
    // update Country
    public function updateCountry($id, $data, $image = null){ // Changed to country
        try {
    
            $query = "
                UPDATE testfabrics_map_countries 
                SET countries__Name = :name,
                    countries__Map_ID = :continent_id
                WHERE countries__ID = :id
            ";
    
            DB::update($query, [
                'name' => $data['countries__Name'],
                'continent_id' => $data['countries__Map_ID'] ?? '',
                'id' => $id,
            ]);

            // Handle the image if provided
            if ($image) {
                $folderName = 'standards_images';
                $fileName = imageUpload($folderName, $image, $id); // Helper function
                if (!$fileName) {
                    throw new \Exception("Image upload failed.");
                }
            }
            
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error updating country: " . $e->getMessage()); // Changed to country
        }
    }
    
    // delete Country
    public function deleteCountry($id){ // Changed to country
        try {
    
            // Delete the record from the database
            $query = "DELETE FROM testfabrics_map_countries WHERE countries__ID = :id";
            $result = DB::delete($query, ['id' => $id]);
    
            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting country: " . $e->getMessage()); // Changed to country
        }
    }
    
}
