@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')


@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="Services"
        :thead="['#', 'Name','Actions']" 
        :route="route('service.index')"
        :createLink="route('service.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex", "searchable" => false, "orderable" => false],
            ["data" => "associations_and_partners__Name", "name" => "associations_and_partners__Name"],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
    
  </div>
</div>
@endsection
