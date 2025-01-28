@extends('layouts/contentNavbarLayout')

@section('title', 'Price Break Up Management')

@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="Price Break Up Management"
        :thead="['#', 'Title', 'Unit', 'Status', 'Actions']" 
        :route="route('price-break-up.index')"
        :createLink="route('price-break-up.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex","searchable" => false],
            ["data" => "price__Title", "name" => "price__Title"],
            ["data" => "price__Units", "name" => "price__Units"],
            ["data" => "price__Status", "name" => "price__Status"],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
  </div>
</div>
@endsection
