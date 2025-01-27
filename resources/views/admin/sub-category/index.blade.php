@extends('layouts/contentNavbarLayout')

@section('title', 'Subcategories')

@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="Subcategory Management"
        :thead="['#', 'Subcategory Name', 'Category Name', 'Sequence', 'Status', 'Actions']" 
        :route="route('sub-category.index')"
        :createLink="route('sub-category.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex", "searchable" => false],
            ["data" => "subcategory__Name", "name" => "subcategory__Name"],
            ["data" => "category__Name", "name" => "category__Name", "searchable" => false], // Updated for the joined column
            ["data" => "subcategory__Sequence", "name" => "subcategory__Sequence"],
            ["data" => "subcategory__Status", "name" => "subcategory__Status"],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'

    />
  </div>
</div>
@endsection
