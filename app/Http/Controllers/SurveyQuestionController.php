<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use App\Models\SurveyQuestion;
use Illuminate\Support\Facades\DB;


class SurveyQuestionController extends Controller
{
    
    public function index(Request $request, $id){
        try {
            if($request->ajax()){
                $questions = (new SurveyQuestion)->getQuestionsForDataTable($id);
                return $questions;
            }
            $getQuestion = (new SurveyQuestion)->getQuestions($id);
            $isQuestionExist = false;
            if($getQuestion) 
                $isQuestionExist = true;

            return view('admin.survey-question.index')->with(['survey_id' => $id,'isQuestionExist' => $isQuestionExist]);
            
        } catch (\Throwable $th) {
            return redirect()->back()
            ->with('error', $th->getMessage());
        }
    }

    public function create($id){
        try {
            $survey = (new Survey)->getSurveyToGetQuestion($id);
            return view('admin.survey-question.create')->with(['survey' => $survey]);
        } catch (\Throwable $th) {
            return redirect()->back()
            ->with('error', $e->getMessage());
        }

    }    
    
    public function save(Request $request,$id){
        try {
            DB::beginTransaction();
            // Loop through POST data to capture dynamic form fields
            foreach ($request->all() as $key => $value) {
                if (preg_match('/^question_(\d+)_text$/', $key, $matches)) {
                    $questionNumber = $matches[1];

                    // Insert question
                    $questionId = DB::table('questions')->insertGetId([
                        'survey_id' => $id,
                        'text' => $request->input("question_{$questionNumber}_text"),
                        'type' => $request->input("question_{$questionNumber}_type"),
                        'is_required' => $request->has("question_{$questionNumber}_required") ? 1 : 0,
                        'created_at' => now(),
                    ]);

                    // Handle options for radio/checkbox types
                    $questionType = $request->input("question_{$questionNumber}_type");
                    if (in_array($questionType, ['radio', 'checkbox'])) {
                        $optionNumber = 1;
                        while ($request->has("question_{$questionNumber}_option_{$optionNumber}_text")) {
                            DB::table('question_options')->insert([
                                'question_id' => $questionId,
                                'text' => $request->input("question_{$questionNumber}_option_{$optionNumber}_text"),
                            ]);
                            $optionNumber++;
                        }
                    }
                }
            }
            DB::commit();
            return redirect()->route('survey.question.index', ['id' => $id])
                            ->with([
                                'survey_id' => $id,
                                'success' => 'Questions saved successfully'
                            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                            ->with('error', $e->getMessage())
                            ->withInput();
        }
    }

    public function edit($id){
        try {
            $survey = (new Survey)->getSurveysById($id);
            if(!$survey) 
                return redirect()->back()->with(['error' => 'Survey is either expire or active']);  
            $questions = (new SurveyQuestion)->getQuestions($id);

            return view('admin.survey-question.edit')->with(['questions' => $questions, 'survey_id' => $id]);
        } catch (\Throwable $th) {
            return redirect()->back()
            ->with('error', $th->getMessage());
        }

    }


    public function update(Request $request, $id){
       // Update the survey statuses
        DB::update("
            UPDATE surveys
            SET status = CASE
                WHEN available_from <= NOW() AND available_until >= NOW() THEN 'active'
                WHEN available_from > NOW() THEN 'upcoming'
                WHEN available_until < NOW() THEN 'expired'
            END
        ");
        
        // Check if the survey exists
        $survey = DB::selectOne("SELECT * FROM surveys WHERE id = ?", [$id]);

        // If the survey does not exist, redirect back
        if (!$survey) {
            return redirect()->route('survey.index')->with('error', 'Survey not found');
        }

        // Check if the survey status is not 'upcoming'
        if ($survey->status !== 'upcoming') {
            return redirect()->route('survey.index')->with('error', 'Survey cannot be updated as it is not "upcoming"');
        }

        try {
            DB::transaction(function () use ($request, $id) {
                // Delete existing questions for the survey
                DB::delete("DELETE FROM questions WHERE survey_id = ?", [$id]);

                // Insert new questions and options
                foreach ($request->all() as $key => $value) {
                    if (preg_match('/^question_(\d+)_text$/', $key, $matches)) {
                        $question_number = $matches[1];

                        // Capture question details
                        $question_text = $request->input("question_{$question_number}_text");
                        $question_type = $request->input("question_{$question_number}_type");
                        $is_required = $request->has("question_{$question_number}_required") ? 1 : 0;

                        // Insert the question
                        DB::insert("
                            INSERT INTO questions (survey_id, text, type, is_required) 
                            VALUES (?, ?, ?, ?)
                        ", [$id, $question_text, $question_type, $is_required]);

                        // Get the last inserted question ID
                        $question_id = DB::getPdo()->lastInsertId();

                        // Insert options for radio/checkbox questions
                        if (in_array($question_type, ['radio', 'checkbox'])) {
                            $i = 1;
                            while ($request->has("question_{$question_number}_option_{$i}_text")) {
                                $option_text = $request->input("question_{$question_number}_option_{$i}_text");

                                if (!empty($option_text)) {
                                    DB::insert("
                                        INSERT INTO question_options (question_id, text) 
                                        VALUES (?, ?)
                                    ", [$question_id, $option_text]);
                                }
                                $i++;
                            }
                        }
                    }
                }
            });

            return redirect()->route('survey.question.index', ['id' => $id])
                ->with('success', 'Survey updated successfully');

        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update survey questions.')
                ->withInput();
        }
    }


}
