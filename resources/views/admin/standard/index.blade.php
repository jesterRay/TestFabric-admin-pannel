@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')


@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="Standard Management"
        :thead="['#', 'Name','Status','Actions']" 
        :route="route('standard.index')"
        :createLink="route('standard.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex", "searchable" => false, "orderable" => false],
            ["data" => "standards__Name", "name" => "standards__Name"],
            ["data" => "standards__Status", "name" => "standards__Status"],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
    
  </div>
</div>
@endsection
