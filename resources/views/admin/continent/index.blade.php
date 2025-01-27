@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')


@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="Continent Management"
        :thead="['#', 'Name','Status','Country','Actions']" 
        :route="route('continent.index')"
        :createLink="route('continent.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex"],
            ["data" => "map__Name", "name" => "map__Name"],
            ["data" => "map__Status", "name" => "map__Status"],
            ["data" => "country", "name" => "country", "orderable" => false, "searchable" => false],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
    
  </div>
</div>
@endsection
