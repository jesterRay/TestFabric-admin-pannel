<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class Event extends Model
{
    
    public function getEvent(){
        try {
            $events = DB::select("SELECT * FROM testfabtics_events ORDER BY events__Name ");
            return $events;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function getEventById($id){
        try {
            $result = DB::select("SELECT * FROM testfabtics_events WHERE events__ID = ? ", [$id]);
    
            if (empty($result)) {
                return null;
            }
    
            $event = $result[0];
    
            // Check if the image exists using the findImage function
            $imagePath = findImage($id, 'events_images');
    
            // Add the image path to the event details, or set it to null if no image found
            $event->imgfile = $imagePath ? $imagePath : null;
    
            return $event;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    // Get events and format them into DataTable format
    public function getEventsForDataTable(){
        try {
            
            $query = DB::table('testfabtics_events');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $edit_link = route('event.edit', $row->events__ID);
                    $delete_link = route('event.destroy', $row->events__ID);
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
    
    // Add Event and its image
    public function addEvent($data, $image = null){
        try {
            $query = "
                INSERT INTO testfabtics_events 
                (events__Name, events__Abbriviation, events__Url, events__Description)
                VALUES (:name, :abbriviation, :url, :description)
            ";
    
            DB::insert($query, [
                'name' => $data['events__Name'],
                'abbriviation' => $data['events__Abbriviation'],
                'url' => $data['events__Url'],
                'description' => $data['events__Description'],
            ]);
    
            // Get the last inserted ID
            $max_id = DB::getPdo()->lastInsertId();
    
            // Upload the image using the helper function
            if ($image) {
                $folderName = 'events_images';
                $fileName = imageUpload($folderName, $image, $max_id); // Helper function
                if (!$fileName) {
                    throw new \Exception("Image upload failed.");
                }
            }
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error adding Event Image: " . $e->getMessage());
        }
    }
    
    // Update Event and its image
    public function updateEvent($id, $data, $image = null){
        try {
            $query = "
                UPDATE testfabtics_events 
                SET events__Name = :name,
                    events__Abbriviation = :abbriviation,
                    events__Url = :url,
                    events__Description = :description
                WHERE events__ID = :id
            ";
    
            DB::update($query, [
                'name' => $data['events__Name'],
                'abbriviation' => $data['events__Abbriviation'],
                'url' => $data['events__Url'],
                'description' => $data['events__Description'],
                'id' => $id,
            ]);
    
            // Handle the image if provided
            if ($image) {
                $folderName = 'events_images';
                $fileName = imageUpload($folderName, $image, $id); // Helper function
                if (!$fileName) {
                    throw new \Exception("Image upload failed.");
                }
            }
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error updating Event: " . $e->getMessage());
        }
    }
    
    // Delete Event and its image
    public function deleteEvent($id){
        try {
            $query = "DELETE FROM testfabtics_events WHERE events__ID = :id";
            $result = DB::delete($query, ['id' => $id]);
    
            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }
    
            // Retrieve the file path for the event image
            $folderName = 'events_images';
    
            // Helper function to delete the image
            deleteImage($folderName, $id);
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting Event: " . $e->getMessage());
        }
    }
    
}
