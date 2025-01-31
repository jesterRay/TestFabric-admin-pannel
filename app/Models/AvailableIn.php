<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class AvailableIn extends Model
{

    public function getAvailableIn(){
        try {
            return DB::select("SELECT * FROM testfabrics_available_in ORDER BY Available__ID DESC");
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getAvailableInById($id){
        try {
            $result = DB::select("SELECT * FROM testfabrics_available_in WHERE Available__ID = ?", [$id]);
            return $result[0] ?? null;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getAvailableInForDataTable(){
        try {
            $query = DB::table('testfabrics_available_in');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $edit_link = route('available-in.edit', $row->Available__ID);
                    $delete_link = route('available-in.destroy', $row->Available__ID);
                    return view('components.action-button', compact('edit_link', 'delete_link'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addAvailableIn($data){
        try {
            $query = "
                INSERT INTO testfabrics_available_in 
                (Available__Name, Available__Status, excel) 
                VALUES (:name, :status, :excel)
            ";

            DB::insert($query, [
                'name' => $data['Available__Name'],
                'status' => $data['Available__Status'],
                'excel' => $data['excel'] ?? 0,
            ]);

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error adding Available In: " . $e->getMessage());
        }
    }

    public function updateAvailableIn($id, $data){
        try {
            $query = "
                UPDATE testfabrics_available_in 
                SET Available__Name = :name, 
                    Available__Status = :status
                WHERE Available__ID = :id
            ";

            DB::update($query, [
                'name' => $data['Available__Name'],
                'status' => $data['Available__Status'],
                'id' => $id,
            ]);

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error updating Available In: " . $e->getMessage());
        }
    }

    public function deleteAvailableIn($id){
        try {
            $query = "DELETE FROM testfabrics_available_in WHERE Available__ID = :id";
            $result = DB::delete($query, ['id' => $id]);

            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting Available In: " . $e->getMessage());
        }
    }

    
}
