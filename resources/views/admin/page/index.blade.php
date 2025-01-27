@extends('layouts/contentNavbarLayout')

@section('title', 'Events')

@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="Page's Management"
        :thead="['#', 'Name','Title','Actions']" 
        :route="route('page.index')"
        :createLink="route('page.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex"],
            ["data" => "page_name", "name" => "page_name"],
            ["data" => "page_title", "name" => "page_title"],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
    
  </div>
</div>
@endsection
