@extends('layouts/contentNavbarLayout')

@section('title', 'Events')

@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="Download Management"
        :thead="['#', 'Name','Actions']" 
        :route="route('download.index')"
        :createLink="route('download.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex", "searchable" => false, "orderable" => false],
            ["data" => "download_name", "name" => "download_name"],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
    
  </div>
</div>
@endsection
