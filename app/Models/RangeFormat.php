<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class RangeFormat extends Model
{
    
    public function getRangeFormats()
    {
        try {
            return DB::select("SELECT * FROM testfabrics_range_format ORDER BY rangeformat__Sequence ASC");
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getRangeFormatById($id)
    {
        try {
            $result = DB::select("SELECT * FROM testfabrics_range_format WHERE rangeformat__ID = ?", [$id]);
            return $result[0] ?? null;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getRangeFormatsForDataTable()
    {
        try {
            $query = DB::table('testfabrics_range_format');
            return DataTables::of($query)
                ->order(function ($query) {
                    $query->orderBy('rangeformat__Name', 'asc');
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $edit_link = route('range-format.edit', $row->rangeformat__ID);
                    $delete_link = route('range-format.destroy', $row->rangeformat__ID);
                    return view('components.action-button', compact('edit_link', 'delete_link'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addRangeFormat($data)
    {
        try {
            $query = "
                INSERT INTO testfabrics_range_format 
                (rangeformat__Name, rangeformat__Sequence, rabgeformat__Status) 
                VALUES (:name, :sequence, :status)
            ";

            DB::insert($query, [
                'name' => $data['rangeformat__Name'],
                'sequence' => $data['rangeformat__Sequence'],
                'status' => $data['rabgeformat__Status'],
            ]);

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error adding Range Format: " . $e->getMessage());
        }
    }

    public function updateRangeFormat($id, $data)
    {
        try {
            $query = "
                UPDATE testfabrics_range_format 
                SET rangeformat__Name = :name, 
                    rangeformat__Sequence = :sequence,
                    rabgeformat__Status = :status
                WHERE rangeformat__ID = :id
            ";

            DB::update($query, [
                'name' => $data['rangeformat__Name'],
                'sequence' => $data['rangeformat__Sequence'],
                'status' => $data['rabgeformat__Status'],
                'id' => $id,
            ]);

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error updating Range Format: " . $e->getMessage());
        }
    }

    public function deleteRangeFormat($id)
    {
        try {
            $query = "DELETE FROM testfabrics_range_format WHERE rangeformat__ID = :id";
            $result = DB::delete($query, ['id' => $id]);

            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting Range Format: " . $e->getMessage());
        }
    }

}
