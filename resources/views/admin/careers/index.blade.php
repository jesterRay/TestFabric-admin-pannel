@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')


@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="Career Management"
        :thead="['#', 'Name', 'Abbreviation','Actions']" 
        :route="route('career.index')"
        :createLink="route('career.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex"],
            ["data" => "career__Name", "name" => "career__Name"],
            ["data" => "career__Abbriviation", "name" => "career__Abbriviation"],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
    
  </div>
</div>
@endsection
