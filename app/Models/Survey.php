<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class Survey extends Model
{
    
    public function checkAndUpdateStatus($survey) {
        $now = now();
        $newStatus = $survey->status;
    
        // Determine the new status
        if ($now->lt($survey->available_from)) {
            $newStatus = 'upcoming';
        } elseif ($now->between($survey->available_from, $survey->available_until)) {
            $newStatus = 'active';
        } else {
            $newStatus = 'expired';
        }
    
        // Update the database only if the status has changed
        if ($survey->status !== $newStatus) {
            DB::table('surveys')
                ->where('id', $survey->id)
                ->update(['status' => $newStatus]);
        }
    }
    
    public function getSurveys() {
        try {
            $surveys = DB::table('surveys')->get(); // Fetch surveys from the database
    
            // Update the status only if needed
            foreach ($surveys as $survey) {
                $this->checkAndUpdateStatus($survey);
            }
    
            // Return the surveys with their updated statuses
            return $surveys;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function getSurveysById($id){
        try {
            $survey = DB::select('SELECT * FROM surveys where id = ? AND status = "upcoming"',[$id]);
            if($survey){
                $this->checkAndUpdateStatus($survey[0]);
                return $survey[0];
            }
            return null;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getSurveyToGetQuestion($id){
        try {
            $survey = DB::select('SELECT * FROM surveys where id = ?',[$id]);
            if($survey){
                $this->checkAndUpdateStatus($survey[0]);
                return $survey[0];
            }
            return null;
        } catch (\Throwable $th) {
            throw $th;
        } 
    }

    // Helper method to generate action links for the DataTable
    private function generateActionLinks($row){
        // Generate the URLs for the actions
        $viewLink = route('survey.view', ['id' => $row->id]);
        $questionLink = route('survey.question', ['id' => $row->id]);
        $editLink = route('survey.edit', ['id' => $row->id]);
        $deleteLink = route('survey.delete', ['id' => $row->id]);
    
        // Returning the HTML for the action column
        return '
            <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="' . $viewLink . '"><i class="bx bx-show me-2"></i> View</a>
                    <a class="dropdown-item" href="' . $questionLink . '"><i class="bx bx-question-mark me-2"></i> Question</a>
                    <a class="dropdown-item" href="' . $editLink . '"><i class="bx bx-edit-alt me-2"></i> Edit</a>
                    <a class="dropdown-item" href="' . $deleteLink . '"><i class="bx bx-trash me-2"></i> Delete</a>
                </div>
            </div>
        ';
    }

    public function surveysForDataTable(){
        try {
            $query = DB::table('surveys');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    // You can generate the links dynamically, assuming you have the data available
                    // $viewLink = route('survey.view', ['id' => $row->id]);
                    // $editLink = route('survey.edit', ['id' => $row->id]);
                    $viewLink = route('survey.preview', ['id' => $row->id]);
                    $questionLink = route('survey.question.index', ['id' => $row->id]);
                    $editLink = route('survey.edit', ['id' => $row->id]);
                    $deleteLink = route('survey.delete', ['id' => $row->id]);
                    $responseLink = route('survey.response.index', ['id' => $row->id]);
                    $copyResponseLink = route('survey.response.create', ['id' => $row->id]);
        
                    // Returning the HTML for the action column
                    return '
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . $viewLink . '"><i class="bx bx-show me-2"></i> Preview</a> 
                                <a class="dropdown-item" href="' . $questionLink . '"><i class="bx bx-question-mark me-2"></i> Question</a> 
                                <a class="dropdown-item" href="' . $editLink . '"><i class="bx bx-edit-alt me-2"></i> Edit</a>
                                <a class="dropdown-item" href="' . $deleteLink . '"><i class="bx bx-trash me-2"></i> Delete</a>
                                <a class="dropdown-item" href="' . $responseLink . '"><i class="bx bx-message-detail me-2"></i> Response</a>
                                 <a class="dropdown-item copy-link" href="javascript:void(0);" data-link="' . $copyResponseLink . '"><i class="bx bx-copy me-2"></i> Copy Response Link</a>
                            </div>
                        </div>
                    ';
                })
                ->make(true);
        } catch (\Throwable $th) {
            throw $th;
        }
    }  

    public function saveSurvey($title, $description, $available_from, $available_until){
        try {
            $status = '';
            $now = now();
            
            // Determine the survey status based on the current time
            if ($now->lt($available_from)) {
                $status = 'upcoming';
            } elseif ($now->between($available_from, $available_until)) {
                $status = 'active';
            } else {
                $status = 'expired';
            }
            
            // Insert data into the surveys table
            $query = "INSERT INTO surveys (title, description, available_from, available_until, status) 
                      VALUES (:title, :description, :available_from, :available_until, :status)";
            
            // Execute the insert query
            $isInserted = DB::insert($query, [
                'title' => $title,
                'description' => $description,
                'available_from' => $available_from,
                'available_until' => $available_until,
                'status' => $status,
            ]);
        
            return $isInserted;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateSurvey($id, $title, $description, $available_from, $available_until){
        try {
            $status = '';
            $now = now();
            
            // Determine the survey status based on the current time
            if ($now->lt($available_from)) {
                $status = 'upcoming';
            } elseif ($now->between($available_from, $available_until)) {
                $status = 'active';
            } else {
                $status = 'expired';
            }

            // Update the survey data in the surveys table
            $query = "UPDATE surveys 
                    SET title = :title, description = :description, available_from = :available_from, 
                        available_until = :available_until, status = :status
                    WHERE id = :id";

            // Execute the update query
            $isUpdated = DB::update($query, [
                'id' => $id,
                'title' => $title,
                'description' => $description,
                'available_from' => $available_from,
                'available_until' => $available_until,
                'status' => $status,
            ]);
            
            return $isUpdated;
            
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteSurvey($id){
        try {
            $isDeleted = DB::delete("DELETE FROM surveys WHERE id = ?",[$id]);
            return $isDeleted;
        } catch (\Throwable $th) {
            throw $th;
        }
    }




}
