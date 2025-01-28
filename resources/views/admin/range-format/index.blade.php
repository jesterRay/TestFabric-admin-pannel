@extends('layouts/contentNavbarLayout')

@section('title', 'Range Format Management')

@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="Range Format Management"
        :thead="['#', 'Name', 'Sequence', 'Status', 'Actions']" 
        :route="route('range-format.index')"
        :createLink="route('range-format.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex","searchable" => false],
            ["data" => "rangeformat__Name", "name" => "rangeformat__Name"],
            ["data" => "rangeformat__Sequence", "name" => "rangeformat__Sequence"],
            ["data" => "rabgeformat__Status", "name" => "rabgeformat__Status"],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
  </div>
</div>
@endsection
