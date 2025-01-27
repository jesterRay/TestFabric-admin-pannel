@extends('layouts/contentNavbarLayout')

@section('title', 'Surveys')

@section('content')

<div class="row">

  <div class="col-12 mb-6">
      @php
        if($isQuestionExist){
          $createLinkRoute = route('survey.question.edit', ['id' => $survey_id]);
          $createLinkRouteText = "Edit";
        }
        else{
          $createLinkRoute = route('survey.question.create', ['id' => $survey_id]);
          $createLinkRouteText = "Add";
        }
      @endphp
        <x-table
            title="Survey's Question"
            :thead="['#', 'Question','Type', 'is Required', 'Options']"
            :route="route('survey.question.index', ['id' => $survey_id])"
            :createLink="$createLinkRoute"
            :createLinkText="$createLinkRouteText"
            :columns='[
                ["data" => "DT_RowIndex", "name" => "DT_RowIndex"],
                ["data" => "question_text", "name" => "question_text"],
                ["data" => "question_type", "name" => "question_type"],
                ["data" => "question_is_required", "name" => "question_is_required"],
                ["data" => "options", "name" => "options"]
            ]'
        />
  </div>
</div>

@endsection
