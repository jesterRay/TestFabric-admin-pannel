@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')

@section('content')

<div class="row">
  <div class="col-12 mb-6">
        <x-table
            title="COC Management"
            :thead="['#', 'Name', 'Coc Files', 'Actions']" 
            :createLink="route('coc.add')"
            createLinkText="New"
            :route="route('coc.index')"
            :columns='[
                ["data" => "DT_RowIndex", "name" => "DT_RowIndex"],
                ["data" => "files__Name", "name" => "files__Name"],
                ["data" => "files__download_name", "name" => "files__download_name"],
                ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
            ]'
        />
  </div>
</div>

@endsection
