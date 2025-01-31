@extends('layouts/contentNavbarLayout')

@section('title', 'Subcategories')

@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
      title="Product Management"
      :thead="['#', 'Product Name','Item', 'Category Name', 'Subcategory Name', 'Actions']" 
      :route="route('product.index')"
      :createLink="route('product.create')"
      createLinkText="Add"
      :columns='[
          ["data" => "DT_RowIndex", "name" => "DT_RowIndex", "searchable" => false, "orderable" => false],
          ["data" => "product__Name", "name" => "product__Name"],
          ["data" => "product__Number", "name" => "product__Number"],
          ["data" => "category_name", "name" => "category_name", "searchable" => false],  // Correct reference to category_name
          ["data" => "subcategory_name", "name" => "subcategory_name", "searchable" => false],  // Correct reference to subcategory_name
          ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
      ]'
  />

  </div>
</div>
@endsection
