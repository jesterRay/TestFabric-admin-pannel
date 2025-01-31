<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class PriceBreakUp extends Model
{
    
    public function getPriceBreakUps()
    {
        try {
            return DB::select("SELECT * FROM testfabrics_price_breakup ORDER BY price__ID ASC");
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getPriceBreakUpById($id)
    {
        try {
            $result = DB::select("SELECT * FROM testfabrics_price_breakup WHERE price__ID = ?", [$id]);
            return $result[0] ?? null;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getPriceBreakUpsForDataTable()
    {
        try {
            $query = DB::table('testfabrics_price_breakup');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $edit_link = route('price-break-up.edit', $row->price__ID);
                    $delete_link = route('price-break-up.destroy', $row->price__ID);
                    return view('components.action-button', compact('edit_link', 'delete_link'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addPriceBreakUp($data)
    {
        try {
            $query = "
                INSERT INTO testfabrics_price_breakup 
                (price__Title, price__Units, price__Range_Format, price__Status) 
                VALUES (:title, :units, :range_format, :status)
            ";

            DB::insert($query, [
                'title' => $data['price__Title'],
                'units' => $data['price__Units'],
                'range_format' => $data['price__Range_Format'],
                'status' => $data['price__Status'],
            ]);

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error adding Price Break Up: " . $e->getMessage());
        }
    }

    public function updatePriceBreakUp($id, $data)
    {
        try {
            $query = "
                UPDATE testfabrics_price_breakup 
                SET price__Title = :title, 
                    price__Units = :units,
                    price__Range_Format = :range_format,
                    price__Status = :status
                WHERE price__ID = :id
            ";

            DB::update($query, [
                'title' => $data['price__Title'],
                'units' => $data['price__Units'],
                'range_format' => $data['price__Range_Format'],
                'status' => $data['price__Status'],
                'id' => $id,
            ]);

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error updating Price Break Up: " . $e->getMessage());
        }
    }

    public function deletePriceBreakUp($id)
    {
        try {
            $query = "DELETE FROM testfabrics_price_breakup WHERE price__ID = :id";
            $result = DB::delete($query, ['id' => $id]);

            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting Price Break Up: " . $e->getMessage());
        }
    }

}
