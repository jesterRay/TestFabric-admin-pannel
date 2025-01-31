<?php

namespace App\Models;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    
    public function getPages() {
        try {
            $pages = DB::select("SELECT * FROM testfabrics_pages ORDER BY page_name");
            return $pages;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function getPageById($id) {
        try {
            $result = DB::select("SELECT * FROM testfabrics_pages WHERE page_id = ?", [$id]);
    
            if (empty($result)) {
                return null;
            }
    
            return $result[0]; // Return the page details
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    // Get pages and format them into DataTable format
    public function getPagesForDataTable() {
        try {
            $pages = $this->getPages();
            $query = DB::table('testfabrics_pages');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $edit_link = route('page.edit', $row->page_id);
                    $delete_link = route('page.destroy', $row->page_id);
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
    
    // Add a new page
    public function addPage($data) {
        try {
            $query = "
                INSERT INTO testfabrics_pages 
                (page_name, page_title, page_keywords, page_description, page_long_content)
                VALUES (:name, :title, :keywords, :description, :long_content)
            ";
    
            DB::insert($query, [
                'name' => $data['page_name'],
                'title' => $data['page_title'],
                'keywords' => $data['page_keywords'],
                'description' => $data['page_description'],
                'long_content' => $data['page_long_content'],
            ]);
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error adding Page: " . $e->getMessage());
        }
    }
    
    // Update an existing page
    public function updatePage($id, $data) {
        try {
            $query = "
                UPDATE testfabrics_pages 
                SET page_name = :name,
                    page_title = :title,
                    page_keywords = :keywords,
                    page_description = :description,
                    page_long_content = :long_content
                WHERE page_id = :id
            ";
    
            DB::update($query, [
                'name' => $data['page_name'],
                'title' => $data['page_title'],
                'keywords' => $data['page_keywords'],
                'description' => $data['page_description'],
                'long_content' => $data['page_long_content'],
                'id' => $id,
            ]);
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error updating Page: " . $e->getMessage());
        }
    }
    
    // Delete a page
    public function deletePage($id) {
        try {
            $query = "DELETE FROM testfabrics_pages WHERE page_id = :id";
            $result = DB::delete($query, ['id' => $id]);
    
            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting Page: " . $e->getMessage());
        }
    }
    
    public function getHomePage(){
        try {

            $page = DB::select("SELECT * FROM testfabtics_feda");
            return $page ? $page[0] : null;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateHomePage($id, $data){ 
        try { 
            return DB::table('testfabtics_feda')
                ->where('id', $id)
                ->update([
                    'heading' => $data['heading'],
                    'text' => $data['text']
                ]); 
        } catch (\Throwable $th) { 
            throw $th; 
        } 
    }

}
