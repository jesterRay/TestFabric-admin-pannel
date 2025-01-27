@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')

@section('content')

<div class="row">
  <div class="col-12 mb-6">
        <x-table 
            title="Associations and Partners's Management"
            :thead="['#', 'Name', 'abbreviation', 'url', 'Actions']" 
            :route="route('association-and-partner.index')"
            :createLink="route('association-and-partner.create')"
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
