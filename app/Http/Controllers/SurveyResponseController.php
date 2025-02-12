<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SurveyResponse;
use Illuminate\Support\Facades\DB;



class SurveyResponseController extends Controller
{

    public function create(Request $request,$id){
        
        try {
            // Validate the survey_id input
        $survey_id = $id;
        if (!$survey_id) {
            return redirect()->route('survey.response.expire');
        }

        // Update survey statuses
        DB::update("
            UPDATE surveys
            SET status = CASE
                WHEN available_from <= NOW() AND available_until >= NOW() THEN 'active'
                WHEN available_from > NOW() THEN 'upcoming'
                WHEN available_until < NOW() THEN 'expired'
            END
        ");

        // Check if the survey exists
        $survey = DB::table('surveys')->where('id', $survey_id)->first();

        // check if survey exist
        // if user request for preview ignore the active check
        // if user not request for preview check the active check
        if (!$survey || (!$request->session()->has('preview') && $survey->status !== 'active')) {
            return redirect()->route('survey.response.expire');
        }


        // Fetch questions for the survey
        $questions = DB::table('questions')
            ->where('survey_id', $survey_id)
            ->get(['id', 'text', 'type', 'is_required']);

        $questionsWithOptions = $questions->map(function ($question) {
            // Fetch options for each question
            $options = DB::table('question_options')
                ->where('question_id', $question->id)
                ->pluck('text');
            
            return [
                'id' => $question->id,
                'question_text' => $question->text,
                'question_type' => $question->type,
                'is_required' => $question->is_required,
                'options' => $options
            ];
        });
        

        return view('admin.survey-response.create')->with(['survey' => $survey, 'questions' => $questionsWithOptions]);
        } catch (\Throwable $th) {
            return $th->getMessage();
            return redirect()->route('survey.response.delete')->with("error", $th->getMessage());
        }
    }

    public function save(Request $request, $id){
        // Update survey status
        DB::update('
            UPDATE surveys
            SET status = CASE
                WHEN available_from <= NOW() AND available_until >= NOW() THEN "active"
                WHEN available_from > NOW() THEN "upcoming"
                WHEN available_until < NOW() THEN "expired"
            END
        ');
    
        DB::beginTransaction();
    
        
        try {
            // Insert a new row into survey_responses and get the inserted response_id
            $responseId = DB::table('survey_responses')->insertGetId([
                'survey_id' => $id
            ]);
    
            // Loop through submitted data to save responses
            foreach ($request->except('_token') as $question_id => $value) {
                if (is_numeric($question_id)) {
                    if (is_array($value)) {
                        // Handle checkbox responses
                        foreach ($value as $answer) {
                            DB::insert('
                                INSERT INTO response_data (response_id, question_id, answer_text) 
                                VALUES (?, ?, ?)
                            ', [$responseId, $question_id, $answer]);
                        }
                    } else {
                        // Handle single responses
                        DB::insert('
                            INSERT INTO response_data (response_id, question_id, answer_text) 
                            VALUES (?, ?, ?)
                        ', [$responseId, $question_id, $value]);
                    }
                }
            }
    
            DB::commit();
    
            // Redirect to thanks page or success message
            return redirect()->route('survey.response.thank');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors('Failed to save survey responses: ' . $e->getMessage());
        }  
    }
    
    public function index(Request $request,$id){

        $survey_response = new SurveyResponse;
        $survey_id = $id;

        
        if($request->ajax()){
            $responses = $survey_response->getResponseForDatable($survey_id);
            return $responses;
        }
        
        $headers = $survey_response->getSurveyQuestionsHeader($survey_id);
        $columns = $survey_response->generateColumnArray($id);

        return view('admin.survey-response.index')->with(['headers' => $headers,"columns" => $columns,'id' => $id]);
    }

    public function expire(){
        return view("admin.survey-response.expire");
    }
    
    public function thank(){
        return view('admin.survey-response.thank');
    }

    public function analytic($id){
        try {
            $analytics = (new SurveyResponse)->getAnalyticsData($id);
            return view('admin.survey-response.analytic', ['responses' => $analytics]);
        } catch (\Throwable $th) {
            
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


      

}
