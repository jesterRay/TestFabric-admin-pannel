@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')


@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="Quick Link's Management"
        :thead="['#', 'Name','Sequence','Status','Actions']" 
        :route="route('quick-link.index')"
        :createLink="route('quick-link.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex", "searchable" => false, "orderable" => false],
            ["data" => "links__Title", "name" => "links__Title"],
            ["data" => "links__Sequence", "name" => "links__Sequence"],
            ["data" => "links__Status", "name" => "links__Status"],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
    
  </div>
</div>
@endsection
