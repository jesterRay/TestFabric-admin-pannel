@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')


@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="Banner Management"
        :thead="['#', 'Name', 'Product Image','Actions']" 
        :route="route('banner.index')"
        :createLink="route('banner.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex"],
            ["data" => "files__Name", "name" => "files__Name"],
            ["data" => "product_image", "name" => "product_image"],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
    
  </div>
</div>
@endsection
