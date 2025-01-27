@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')


@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="Career Management"
        :thead="['#', 'Name', 'Sequence','Status','Actions']" 
        :route="route('test-method.index')"
        :createLink="route('test-method.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex"],
            ["data" => "methods__Name", "name" => "methods__Name"],
            ["data" => "methods__Sequence", "name" => "methods__Sequence"],
            ["data" => "methods__Status", "name" => "methods__Status"],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
    
  </div>
</div>
@endsection
