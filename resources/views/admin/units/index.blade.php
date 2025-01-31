@extends('layouts/contentNavbarLayout')

@section('title', 'Units Management')

@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="Units Management"
        :thead="['#', 'Name', 'Sequence', 'Status', 'Actions']" 
        :route="route('unit.index')"
        :createLink="route('unit.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex","searchable" => false, "orderable" => false],
            ["data" => "priceunit__Name", "name" => "priceunit__Name"],
            ["data" => "priceunit__Sequence", "name" => "priceunit__Sequence"],
            ["data" => "priceunit__Status", "name" => "priceunit__Status"],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
  </div>
</div>
@endsection
