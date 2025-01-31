<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class Continent extends Model
{
    public function getContinent(){
        try {
            $continents = DB::select("SELECT * FROM testfabrics_map ORDER BY map__Name ");
            return $continents;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function getContinentById($id){
        try {
            $result = DB::select("SELECT * FROM testfabrics_map where map__ID = ? ",[$id]);
    
            return $result[0] ?? null;
            
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    // get Continent and format it into DataTable format
    public function getContinentForDataTable(){
        try {
            $query = DB::table('testfabrics_map');
            return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('country',function($row){
                $link = route('country.index', $row->map__ID); // Replace 'country.show' with your actual route
                return '<a href="' . $link . '"> View country </a>';
            })
            ->addColumn('action', function($row) {
                $edit_link = route('continent.edit', $row->map__ID);
                $delete_link = route('continent.destroy', $row->map__ID);
                $view_link = '';
                return view('components.action-button', 
                            compact('edit_link', 'delete_link', 'view_link'))
                            ->render();
            })
            ->rawColumns(['country','action'])
            ->make(true);
    
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    // add Continent and its image
    public function addContinent($data){
        try {
    
            // Insert the record into the database
            $query = "
                INSERT INTO testfabrics_map 
                (map__Name, map__Status, map__Sorting)
                VALUES (:name, :status, :sorting)
            ";

            $mapCount = DB::table('testfabrics_map')->count();

            DB::insert($query, [
                'name' => $data['map__Name'],
                'status' => $data['map__Status'] ?? '',
                'sorting' => ($mapCount + 1),
            ]);
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception( $e->getMessage());
        }
    }   
    
    // update Continent
    public function updateContinent($id, $data){
        try {
    
            $query = "
                UPDATE testfabrics_map 
                SET map__Name = :name,
                    map__Status = :status
                WHERE map__ID = :id
            ";
    
            DB::update($query, [
                'name' => $data['map__Name'],
                'status' => $data['map__Status'] ?? '',
                'id' => $id,
            ]);
    
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error updating continent: " . $e->getMessage());
        }
    }
    
    // delete Continent
    public function deleteContinent($id){
        try {
    
            // Delete the record from the database
            $query = "DELETE FROM testfabrics_map WHERE map__ID = :id";
            $result = DB::delete($query, ['id' => $id]);
    
            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting continent: " . $e->getMessage());
        }
    }
    
}
