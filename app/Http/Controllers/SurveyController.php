<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{

    public function index(Request $request){
        try {
            DB::update("
                    UPDATE surveys
                    SET status = CASE
                        WHEN available_from <= NOW() AND available_until >= NOW() THEN 'active'
                        WHEN available_from > NOW() THEN 'upcoming'
                        WHEN available_until < NOW() THEN 'expired'
                    END
            ");
            
            if($request->ajax()){
                $surveys = (new Survey)->surveysForDataTable();
                return $surveys;
            }
            return view('admin.survey.index');
        } catch (\Throwable $th) {
            return view('admin.survey.index')->with(['error' => $th->getMessage()]);
        }
    }

    public function preview($id){
        return redirect()->route('survey.response.create',['id' => $id])->with(['preview' => true]);
    }

    public function create(){
        return view('admin.survey.create');
    }

    public function save(Request $request){
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'available_from' => 'required|date',
                'available_until' => 'required|date|after:available_from|after:now',
            ]);
            
            $survey = new Survey;
            $survey->saveSurvey(
                $request->title,
                $request->description,
                $request->available_from,
                $request->available_until
            );
            return redirect()->route('survey.index');

        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }

    }

    public function edit($id){
        try {
            $survey = (new Survey)->getSurveysById($id);
            if($survey){
                if($survey->status == "upcoming")
                    return view('admin.survey.edit')->with(['survey' => $survey]);
            }
            return redirect()->back()->with(["error" => 'Status of survey you are trying to edit is not "Upcoming"']);

        } catch (\Throwable $th) {
            return redirect()->back()->with(["error" => $th->getMessage()]);
        }
    }

    public function update(Request $request, $id){
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'available_from' => 'required|date',
                'available_until' => 'required|date|after:available_from|after:now',
            ]);
            
            $survey = new Survey;
            $survey->updateSurvey(
                $id,
                $request->title,
                $request->description,
                $request->available_from,
                $request->available_until
            );

            return redirect()->route('survey.index');

        } catch (\Throwable $th) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
        
    }

    public function delete($id){
        try {
            (new Survey)->deleteSurvey($id);
            return redirect()->route('survey.index')->with('success',"Delete the survey successfully");
        } catch (\Throwable $th) {
            return redirect()->back()->with("error","Error in Deleting Survey");
        }
    }
}
