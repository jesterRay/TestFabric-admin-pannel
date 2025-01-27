@extends('layouts/contentNavbarLayout')

@section('title', 'Events')

@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="Event's Management"
        :thead="['#', 'Name','Abbriviation','Url','Actions']" 
        :route="route('event.index')"
        :createLink="route('event.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex"],
            ["data" => "events__Name", "name" => "events__Name"],
            ["data" => "events__Abbriviation", "name" => "events__Abbriviation"],
            ["data" => "events__Url", "name" => "events__Url"],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
    
  </div>
</div>
@endsection
