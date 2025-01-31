<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class Unit extends Model
{
    
    public function getUnits()
    {
        try {
            return DB::select("SELECT * FROM testfabrics_price_units ORDER BY priceunit__Sequence ASC");
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getUnitById($id)
    {
        try {
            $result = DB::select("SELECT * FROM testfabrics_price_units WHERE priceunit__ID = ?", [$id]);
            return $result[0] ?? null;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getUnitsForDataTable()
    {
        try {
            $query = DB::table('testfabrics_price_units');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $edit_link = route('unit.edit', $row->priceunit__ID);
                    $delete_link = route('unit.destroy', $row->priceunit__ID);
                    return view('components.action-button', compact('edit_link', 'delete_link'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addUnit($data)
    {
        try {
            $query = "
                INSERT INTO testfabrics_price_units 
                (priceunit__Name, priceunit__Sequence, priceunit__Status) 
                VALUES (:name, :sequence, :status)
            ";

            DB::insert($query, [
                'name' => $data['priceunit__Name'],
                'sequence' => $data['priceunit__Sequence'],
                'status' => $data['priceunit__Status'],
            ]);

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error adding Unit: " . $e->getMessage());
        }
    }

    public function updateUnit($id, $data)
    {
        try {
            $query = "
                UPDATE testfabrics_price_units 
                SET priceunit__Name = :name, 
                    priceunit__Sequence = :sequence,
                    priceunit__Status = :status
                WHERE priceunit__ID = :id
            ";

            DB::update($query, [
                'name' => $data['priceunit__Name'],
                'sequence' => $data['priceunit__Sequence'],
                'status' => $data['priceunit__Status'],
                'id' => $id,
            ]);

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error updating Unit: " . $e->getMessage());
        }
    }

    public function deleteUnit($id)
    {
        try {
            $query = "DELETE FROM testfabrics_price_units WHERE priceunit__ID = :id";
            $result = DB::delete($query, ['id' => $id]);

            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting Unit: " . $e->getMessage());
        }
    }

}
