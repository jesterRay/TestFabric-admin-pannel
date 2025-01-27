@extends('layouts/contentNavbarLayout')

@section('title', 'Surveys')

@section('content')

<div class="row">

  <div class="col-12 mb-6">
        <x-table
            title="Survey's response"
            :thead="$headers"
            :route="route('survey.response.index', ['id' => $id])"
            :createLink="route('survey.response.analytic', ['id' => $id])"
            createLinkText="Analytics"
            :columns='$columns'
        />
  </div>
</div>

@endsection
