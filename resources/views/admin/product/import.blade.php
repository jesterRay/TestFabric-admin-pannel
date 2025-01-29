@extends('layouts/contentNavbarLayout')

@section('title', 'Add Product')

@section('content')

@php
    $statusOptions = collect([
        (object) ['id' => 'Available', 'name' => 'Available'],
        (object) ['id' => 'Unavailable', 'name' => 'Unavailable'],
    ]);
@endphp

<div class="row">
  <div class="col-12 mb-6">
    <div class="card mb-6">
        <div class="card-body pt-4">
          <div class="card-title text-primary h4 mb-6">Import Product From Excelsheet</div>
          <form method="POST" action="{{ route('product.import.save') }}">
            @csrf
            <div class="row g-6">

                <!-- Subcategory Dropdown -->
                <div class="col-md-6">
                    <x-select-two-drop-down
                        name="subcategory"
                        title="Subcategory *"
                        placeholder="Select..."
                        :options="$subcategories"
                        :selected="old('subcategory')"
                        :multiple="false"
                    />
                </div>

                <!-- File-->
                <div class="col-md-6">
                    <label for="excel_file" class="form-label">File *</label>
                    <input 
                        class="form-control" 
                        type="file" 
                        id="excel_file" 
                        name="excel_file"
                        accept=".xlsx"
                    />
                </div>


            </div>

            <div class="mt-6">
              <button type="submit" class="btn btn-primary me-3">Import</button>
            </div>
          </form>
        </div>
      </div>
  </div>
</div>

@endsection
