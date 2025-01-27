@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')


@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="Interest Group Management"
        :thead="['#', 'Name', 'Order', 'Status','Actions']" 
        :route="route('interest-group.index')"
        :createLink="route('interest-group.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex"],
            ["data" => "menu_Name", "name" => "menu_Name"],
            ["data" => "menu_Order", "name" => "menu_Order"],
            ["data" => "menu_Status", "name" => "menu_Status"],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
    
  </div>
</div>
@endsection
