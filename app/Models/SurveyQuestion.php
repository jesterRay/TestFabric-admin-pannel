<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;


class SurveyQuestion extends Model
{

    public function formatQuestionsOptions($questions){
        try {
            // Check if questions array is not empty
            if (!empty($questions)) {
                // Loop through each question
                foreach ($questions as &$question) {
                    // Check if options exist and is an array
                    if (isset($question['options']) && is_array($question['options'])) {
                        // Convert options array to comma-separated string
                        $question['options'] = implode(', ', $question['options']);
                    }
                    if (isset($question['question_is_required'])) {
                        // Convert options array to comma-separated string
                        $question['question_is_required'] = $question['question_is_required'] == 1 ? 'true' : 'false';
                    }

                }
            }
            
            return $questions;
        } catch (\Exception $e) {
            \Log::error('Error formatting options: ' . $e->getMessage());
            throw $e;
        }
    }
    public function getQuestions($id){
        try {
            $result = null;
            $questions = null;
    
            if (!empty($id)) {
                // Update survey status
                DB::update("
                    UPDATE surveys
                    SET status = CASE
                        WHEN available_from <= NOW() AND available_until >= NOW() THEN 'active'
                        WHEN available_from > NOW() THEN 'upcoming'
                        WHEN available_until < NOW() THEN 'expired'
                    END
                ");
    
                // Get survey questions with options
                $result = DB::select("
                    SELECT 
                        q.id AS question_id,
                        q.text AS question_text,
                        q.type AS question_type,
                        q.is_required AS question_is_required,
                        o.id AS option_id,
                        o.text AS option_text
                    FROM 
                        questions q
                    LEFT JOIN 
                        question_options o ON q.id = o.question_id
                    WHERE 
                        q.survey_id = ?
                ", [$id]);
    
                // Process the results
                if (!empty($result)) {
                    $questions = [];
                    
                    foreach ($result as $row) {
                        $question_id = $row->question_id;
                        
                        // If question not already in array, add it
                        if (!isset($questions[$question_id])) {
                            $questions[$question_id] = [
                                'question_id' => $question_id,
                                'question_text' => $row->question_text,
                                'question_type' => $row->question_type,
                                'question_is_required' => $row->question_is_required,
                                'options' => []
                            ];
                        }
                        
                        // If option exists, add it to options array
                        if ($row->option_id) {
                            $questions[$question_id]['options'][] = $row->option_text;
                        }
                    }
                    
                    // Convert to indexed array
                    $questions = array_values($questions);
                }
            }
    
            return $questions;
    
        } catch (\Exception $e) {
            \Log::error('Error fetching questions: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getQuestionsForDataTable($id){
        try {
            $questions = $this->getQuestions($id);
            $formatted_questions = $this->formatQuestionsOptions($questions);
            if($formatted_questions)
                return DataTables::of($formatted_questions)->addIndexColumn()->make(true);
            return DataTables::of([])->addIndexColumn()->make(true);
            
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    
}
