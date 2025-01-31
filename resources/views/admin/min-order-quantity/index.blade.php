@extends('layouts/contentNavbarLayout')

@section('title', 'Minimum Order Quantity Management')

@section('content')
<div class="row">
  <div class="col-12">
    <x-table 
        title="Minimum Order Quantity Management"
        :thead="['#', 'Name', 'Status', 'Actions']" 
        :route="route('min-order-quantity.index')"
        :createLink="route('min-order-quantity.create')"
        createLinkText="Add"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex","searchable" => false, "orderable" => false],
            ["data" => "Min__Name", "name" => "Min__Name"],
            ["data" => "Min__Status", "name" => "Min__Status"],
            ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
        ]'
    />
  </div>
</div>
@endsection
