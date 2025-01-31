@extends('layouts/contentNavbarLayout')

@section('title', 'Available Index')

@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="Available In Management"
        :thead="['#', 'Available Name', 'Status', 'Actions']" 
        :route="route('available-in.index')"
        :createLink="route('available-in.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex","searchable" => false, "orderable" => false],
            ["data" => "Available__Name", "name" => "Available__Name"],
            ["data" => "Available__Status", "name" => "Available__Status"],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
  </div>
</div>
@endsection
