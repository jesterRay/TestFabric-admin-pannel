@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')


@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="About us management"
        :thead="['#', 'Name', 'Abbreviation', 'Url','Actions']" 
        :route="route('about-us.index')"
        :createLink="route('about-us.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex"],
            ["data" => "name", "name" => "name"],
            ["data" => "abbreviation", "name" => "abbreviation"],
            ["data" => "url", "name" => "url"],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
    
  </div>
</div>
@endsection
