@extends('layouts/contentNavbarLayout')

@section('title', 'Subcategories')


@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-6">
            <div class="card-body pt-4">
                <div class="card-title text-primary h4 mb-6">Assign Standards</div>
                <form method="POST" action="{{ route('product.standard.save',['id' => $product_id]) }}">
                    @csrf
                    <div class="row g-6">
                        <!-- Standard Dropdown -->
                        <div class="col-md-6">
                            <x-select-two-drop-down
                                name="standard_id"
                                title="Standard *"
                                placeholder="Select..."
                                :options="$standardOptions"
                                :selected="old('standard_id')"
                                :multiple="false"
                            />
                        </div>
 
                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary me-3">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <x-table 
            title="Assigned Standards Management"
            :thead="['#', 'Standard Name', 'Method Name', 'Actions']" 
            :route="route('product.standard.index',['id' => $product_id])"
            :columns='[
                ["data" => "DT_RowIndex", "name" => "DT_RowIndex", "searchable" => false],
                ["data" => "standards__Name", "name" => "standards__Name"],
                ["data" => "methods__Name", "name" => "methods__Name"],
                ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false]
            ]'
        />
    </div>
    
</div>
@endsection
