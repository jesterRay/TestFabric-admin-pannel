<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;


class SurveyResponse extends Model
{
    


    // to get the header array for datatable
    public function getSurveyQuestionsHeader($id){
        // Fetch all question texts for the given survey_id
        $questions = DB::table('questions')
            ->where('survey_id', $id)
            ->pluck('text');

        // Convert the collection to an array
        $questionsArray = $questions->toArray();

        // Add '#' at the beginning and 'Action' at the end
        array_unshift($questionsArray, '#');

        return $questionsArray;
    }

    // get the question id
    public function getQuestionIdsBySurveyId($survey_id){
        try {
            // Fetch question IDs for the given survey_id
            $questionIds = DB::table('questions')
                ->where('survey_id', $survey_id)
                ->pluck('id') // Get only the `id` column
                ->toArray();  // Convert the result to an array

            return $questionIds;
        } catch (\Exception $e) {
            throw $e->getMessage();
        }
    }

    // Function to generate the column array
    public function generateColumnArray($id){
        $questionIds = $this->getQuestionIdsBySurveyId($id);
        try {
            // Initialize the result array with the DT_RowIndex column
            $columns = [
                ["data" => "DT_RowIndex", "name" => "DT_RowIndex"]
            ];

            // Iterate through question IDs and build the structure
            foreach ($questionIds as $id) {
                $columns[] = ["data" => (string)$id, "name" => (string)$id];
            }

            return $columns;
        } catch (\Exception $e) {
            throw $e->getMessage();
        }
    }

    public function getResponses($survey_id){
        try {
            // Update survey statuses
            DB::update("
                UPDATE surveys
                SET status = CASE
                    WHEN available_from <= NOW() AND available_until >= NOW() THEN 'active'
                    WHEN available_from > NOW() THEN 'upcoming'
                    WHEN available_until < NOW() THEN 'expired'
                END
            ");

            // Get responses with response_id
            $responses = DB::table('response_data as r')
                ->join('questions as q', 'r.question_id', '=', 'q.id')
                ->where('q.survey_id', $survey_id)
                ->select('r.response_id', 'r.question_id', 'q.text as question_text', 
                        'r.answer_text', 'q.type as question_type')
                ->orderBy('r.response_id') // Keep responses grouped
                ->get();
            return $responses;

        } catch (\Exception $e) {
            throw $e->getMessage();
        }
    }

    // transfor response data into format use for datatable 
    public function transformResponsesForDataTable($responses){
        $formatted = [];

        foreach ($responses as $response) {
            // Ensure the group for the response_id exists
            if (!isset($formatted[$response->response_id])) {
                $formatted[$response->response_id] = [];
            }

            // If the question_id already exists, append the answer_text (for checkboxes)
            if (isset($formatted[$response->response_id][$response->question_id])) {
                $formatted[$response->response_id][$response->question_id] .= ', ' . $response->answer_text;
            } else {
                // Otherwise, initialize the question_id with the answer_text
                $formatted[$response->response_id][$response->question_id] = $response->answer_text;
            }
        }

        // Reindex to return as an array of objects
        return array_values($formatted);
    }

    public function getResponseForDatable($id){
        $responses = $this->getResponses($id);
        $formated_responses = $this->transformResponsesForDataTable($responses);
        return DataTables::of($formated_responses)
                ->addIndexColumn()
                ->make(true);
    }

    public function getAnalyticsData($survey_id){
        try {
            $questions = [];
            $questionQuery = "
                SELECT id, text AS question_text, type AS question_type 
                FROM questions 
                WHERE survey_id = ?";

            $questionResults = DB::select($questionQuery, [$survey_id]);

            // Collect all questions
            foreach ($questionResults as $row) {
                $questions[$row->id] = [
                    'question_id' => $row->id,
                    'question_text' => $row->question_text,
                    'question_type' => $row->question_type,
                    'answers' => [], // Prepare an empty array to hold the answers
                    'options' => []  // Prepare an empty array to hold the options if applicable
                ];
            }

            // Query to get all options for questions that have options (checkbox, radio, etc.)
            $optionQuery = "
                SELECT question_id, text AS option_text 
                FROM question_options 
                WHERE question_id IN (SELECT id FROM questions WHERE survey_id = ?)";
            $optionResults = DB::select($optionQuery, [$survey_id]);

            // Collect options for each question that has options
            foreach ($optionResults as $row) {
                if (isset($questions[$row->question_id])) {
                    $questions[$row->question_id]['options'][] = $row->option_text; // Add options to the corresponding question
                }
            }

            // Query to get all responses for the given survey
            $responseQuery = "
                SELECT r.response_id, r.question_id, r.answer_text
                FROM response_data r
                JOIN questions q ON r.question_id = q.id
                WHERE q.survey_id = ?";
            $responseResults = DB::select($responseQuery, [$survey_id]);

            // Process the response data and group answers by question
            foreach ($responseResults as $row) {
                if (isset($questions[$row->question_id])) {
                    $questions[$row->question_id]['answers'][] = $row->answer_text;
                }
            }

            // Reset keys to be sequential
            return array_values($questions);

        } catch (\Exception $e) {
            // Log the error or throw it to the outer catch block
            throw new \Exception('Error fetching survey details: ' . $e->getMessage());
        }
    }


}
