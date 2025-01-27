@extends('layouts/contentNavbarLayout')

@section('title', 'News Management')

@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="News Management"
        :thead="['#', 'Title', 'Date', 'Status', 'Actions']" 
        :route="route('news.index')"
        :createLink="route('news.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex"],
            ["data" => "news__Title", "name" => "news__Title"],
            ["data" => "news__Date", "name" => "news__Date"],
            ["data" => "news__Status", "name" => "news__Status"],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
  </div>
</div>
@endsection
