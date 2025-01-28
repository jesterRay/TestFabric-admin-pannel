<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class MinimumOrderQuantity extends Model
{

    public function getMinimumOrderQuantity(){
        try {
            return DB::select("SELECT * FROM testfabrics_min_quantity ORDER BY Min__ID DESC");
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getMinimumOrderQuantityById($id){
        try {
            $result = DB::select("SELECT * FROM testfabrics_min_quantity WHERE Min__ID = ?", [$id]);
            return $result[0] ?? null;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getMinimumOrderQuantityForDataTable(){
        try {
            $query = DB::table('testfabrics_min_quantity');
            return DataTables::of($query)
                ->order(function ($query) {
                    $query->orderBy('Min__Name', 'asc');
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $edit_link = route('min-order-quantity.edit', $row->Min__ID);
                    $delete_link = route('min-order-quantity.destroy', $row->Min__ID);
                    return view('components.action-button', compact('edit_link', 'delete_link'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addMinimumOrderQuantity($data){
        try {
            $query = "
                INSERT INTO testfabrics_min_quantity 
                (Min__Name, Min__Status, excel) 
                VALUES (:name, :status, :excel)
            ";

            DB::insert($query, [
                'name' => $data['Min__Name'],
                'status' => $data['Min__Status'],
                'excel' => $data['excel'] ?? 0,
            ]);

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error adding Minimum Order Quantity: " . $e->getMessage());
        }
    }

    public function updateMinimumOrderQuantity($id, $data){
        try {
            $query = "
                UPDATE testfabrics_min_quantity 
                SET Min__Name = :name, 
                    Min__Status = :status,
                    excel = :excel
                WHERE Min__ID = :id
            ";

            DB::update($query, [
                'name' => $data['Min__Name'],
                'status' => $data['Min__Status'],
                'excel' => $data['excel'] ?? 0,
                'id' => $id,
            ]);

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error updating Minimum Order Quantity: " . $e->getMessage());
        }
    }

    public function deleteMinimumOrderQuantity($id){
        try {
            $query = "DELETE FROM testfabrics_min_quantity WHERE Min__ID = :id";
            $result = DB::delete($query, ['id' => $id]);

            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting Minimum Order Quantity: " . $e->getMessage());
        }
    }

}
