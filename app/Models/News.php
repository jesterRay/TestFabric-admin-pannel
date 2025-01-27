<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class News extends Model
{

    public function getNews(){
        try {
            $news = DB::select("SELECT * FROM testfabrics_news ORDER BY news__Date DESC");
            return $news;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function getNewsById($id){
        try {
            $result = DB::select("SELECT * FROM testfabrics_news WHERE news__ID = ?", [$id]);
    
            if (empty($result)) {
                return null;
            }
            $news = $result[0];
            
            // Check if the image exists using the findImage function
            $imagePath = findImage($id, 'news_images');
            
            // Add the image path to the news details, or set it to null if no image found
            $news->imgfile = $imagePath ? $imagePath : null;
    
            return $news;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    // get News and format it into DataTable format
    public function getNewsForDataTable(){
        try {
            $query = DB::table('testfabrics_news');
            return DataTables::of($query)
                ->order(function ($query) {
                    $query->orderBy('news__Title', 'asc');
                })
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $edit_link = route('news.edit', $row->news__ID);
                    $delete_link = route('news.destroy', $row->news__ID);
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
    
    // add News and its image
    public function addNews($data, $image){
        try {
            // Insert the record into the database
            $query = "
                INSERT INTO testfabrics_news 
                (news__Title, news__Short_Description, news__Long_Description, news__Date, news__Status)
                VALUES (:title, :short_description, :long_description, :date, :status)
            ";
    
            DB::insert($query, [
                'title' => $data['news__Title'],
                'short_description' => $data['news__Short_Description'],
                'long_description' => $data['news__Long_Description'],
                'date' => $data['news__Date'],
                'status' => $data['news__Status'],
            ]);
    
            // Get the last inserted ID
            $max_id = DB::getPdo()->lastInsertId();
    
            // Upload the image using the helper function
            if ($image) {
                $folderName = 'news_images';
                $fileName = imageUpload($folderName, $image, $max_id); // Helper function
                if (!$fileName) {
                    throw new \Exception("Image upload failed.");
                }
            }
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error adding News Image: " . $e->getMessage());
        }
    }
    
    // update News
    public function updateNews($id, $data, $image = null){
        try {
            $query = "
                UPDATE testfabrics_news 
                SET news__Title = :title,
                    news__Short_Description = :short_description,
                    news__Long_Description = :long_description,
                    news__Date = :date,
                    news__Status = :status
                WHERE news__ID = :id
            ";
    
            DB::update($query, [
                'title' => $data['news__Title'],
                'short_description' => $data['news__Short_Description'],
                'long_description' => $data['news__Long_Description'],
                'date' => $data['news__Date'],
                'status' => $data['news__Status'],
                'id' => $id,
            ]);
    
            // Handle the image if provided
            if ($image) {
                $folderName = 'news_images';
                $fileName = imageUpload($folderName, $image, $id); // Helper function
                if (!$fileName) {
                    throw new \Exception("Image upload failed.");
                }
            }
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error updating news: " . $e->getMessage());
        }
    }
    
    // delete News
    public function deleteNews($id){
        try {
            // Delete the record from the database
            $query = "DELETE FROM testfabrics_news WHERE news__ID = :id";
            $result = DB::delete($query, ['id' => $id]);
    
            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }
    
            // Retrieve the file path for the news image
            $folderName = 'news_images';
    
            // Helper function to delete the image
            deleteImage($folderName, $id);
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting news: " . $e->getMessage());
        }
    }
    



    
    public function getNewsLetterForDataTable(){
        try {
            $query = DB::table("testfabrics_newsletters")->select("newsletter__Email");
            return DataTables::of($query)
            ->order(function ($query) {
                $query->orderBy('newsletter__Email', 'asc');
            })
            ->addIndexColumn()
            ->make(true);


        } catch (\Throwable $th) {
            // dd($th);
            throw $th;
        }
    }

}
