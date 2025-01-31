<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class QuickLink extends Model
{


    public function getQuickLinks(){
        try {
            return DB::select("SELECT * FROM testfabrics_usefullinks ORDER BY links__Sequence DESC");
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function getQuickLinkById($id){
        try {
            $result = DB::select("SELECT * FROM testfabrics_usefullinks WHERE links__ID = ?", [$id]);
            return $result[0] ?? null;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function getQuickLinksForDataTable(){
        try {
            $query = DB::table('testfabrics_usefullinks');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $edit_link = route('quick-link.edit', $row->links__ID);
                    $delete_link = route('quick-link.destroy', $row->links__ID);
                    return view('components.action-button', 
                        compact('edit_link', 'delete_link'))
                        ->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function addQuickLink($data) {
        try {
            $query = "
                INSERT INTO testfabrics_usefullinks 
                (links__Title, links__URL, links__Sequence, links__Status)
                VALUES (:title, :url, :sequence, :status)
            ";
    
            DB::insert($query, [
                'title' => $data['links__Title'],
                'url' => $data['links__URL'],
                'sequence' => $data['links__Sequence'],
                'status' => $data['links__Status']
            ]);
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error adding Quick Link: " . $e->getMessage());
        }
    }
    
    public function updateQuickLink($id, $data) {
        try {
            $query = "
                UPDATE testfabrics_usefullinks 
                SET links__Title = :title,
                    links__URL = :url,
                    links__Sequence = :sequence,
                    links__Status = :status
                WHERE links__ID = :id
            ";
    
            DB::update($query, [
                'title' => $data['links__Title'],
                'url' => $data['links__URL'],
                'sequence' => $data['links__Sequence'],
                'status' => $data['links__Status'],
                'id' => $id
            ]);
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error updating Quick Link: " . $e->getMessage());
        }
    }
    
    public function deleteQuickLink($id) {
        try {
            $query = "DELETE FROM testfabrics_usefullinks WHERE links__ID = :id";
            $result = DB::delete($query, ['id' => $id]);
    
            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }
            
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting Quick Link: " . $e->getMessage());
        }
    }

}
