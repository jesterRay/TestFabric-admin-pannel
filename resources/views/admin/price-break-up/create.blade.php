@extends('layouts/contentNavbarLayout')

@section('title', 'Add Price Breakup')

@section('content')

{{-- @dd('categories: ', $categories) --}}

@php
    $statusOptions = collect([
        (object) ['id' => 'Show', 'name' => 'Show'],
        (object) ['id' => 'Hide', 'name' => 'Hide'],
    ]);
@endphp

<div class="row">
  <div class="col-12 mb-6">
    <div class="card mb-6">
        <div class="card-body pt-4">
          <div class="card-title text-primary h4 mb-6">Create Price Breakup</div>
          <form method="POST" action="{{ route('price-break-up.save') }}">
            @csrf
            <div class="row g-6">
                <!-- Price Title Name -->
                <div class="col-md-6">
                    <label for="price__Title" class="form-label">Ttile *</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="price__Title" 
                        name="price__Title" 
                        value="{{ old('price__Title') }}" 
                        required
                    />
                </div>
              
                <!-- Price Unit Name -->
                <div class="col-md-6">
                    <label for="price__Units" class="form-label">Unit *</label>
                    <input 
                        class="form-control" 
                        type="number" 
                        id="price__Units" 
                        name="price__Units" 
                        value="{{ old('price__Units') }}" 
                        required
                    />
                </div>

                <!-- Price Range Format Name -->
                <div class="col-md-6">
                    <label for="price__Range_Format" class="form-label">Range Format *</label>
                    <input 
                        class="form-control" 
                        type="number" 
                        id="price__Range_Format" 
                        name="price__Range_Format" 
                        value="{{ old('price__Range_Format') }}" 
                        required
                    />
                </div>


                <!-- Price Status Dropdown -->
                <div class="col-md-6">
                    <x-select-two-drop-down
                        name="price__Status"
                        title="Status *"
                        placeholder="Select Status"
                        :options="$statusOptions"
                        :selected="old('price__Status')"
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

@endsection
