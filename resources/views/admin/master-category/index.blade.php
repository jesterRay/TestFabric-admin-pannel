@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')

@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="Career Management"
        :thead="['#', 'Category Name', 'Category Sequence', 'Card View', 'Status', 'Actions']" 
        :route="route('category.index')"
        :createLink="route('category.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex","searchable" => false],
            ["data" => "category__Name", "name" => "category__Name"],
            ["data" => "category__Sequence", "name" => "category__Sequence"],
            ["data" => "card_view", "name" => "card_view"],
            ["data" => "category__Status", "name" => "category__Status"],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
  </div>
</div>
@endsection
