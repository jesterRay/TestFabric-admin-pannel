@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Units')

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
          <div class="card-title text-primary h4 mb-6">Edit Units</div>
          <form method="POST" action="{{ route('unit.update',['id' => $unit->priceunit__ID]) }}">
            @csrf
            @method("PUT")
            <div class="row g-6">
                <!-- Price Unit Name -->
                <div class="col-md-6">
                    <label for="priceunit__Name" class="form-label">Name *</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="priceunit__Name" 
                        name="priceunit__Name" 
                        value="{{ old('priceunit__Name', $unit->priceunit__Name) }}" 
                        required
                    />
                </div>
                
                
                <!-- Price Unit Sequence -->
                <div class="col-md-6">
                    <label for="priceunit__Sequence" class="form-label">Sequence *</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="priceunit__Sequence" 
                        name="priceunit__Sequence" 
                        value="{{ old('priceunit__Sequence', $unit->priceunit__Sequence) }}" 
                        required
                    />
                </div>


                <!-- Price Unit Status Dropdown -->
                <div class="col-md-6">
                    <x-select-two-drop-down
                        name="priceunit__Status"
                        title="Status *"
                        placeholder="Select Status"
                        :options="$statusOptions"
                        :selected="old('priceunit__Status',$unit->priceunit__Status)"
                        :multiple="false"
                    />
                </div>

            <div class="mt-6">
              <button type="submit" class="btn btn-primary me-3">Update</button>
            </div>
          </form>
        </div>
      </div>
  </div>
</div>

@endsection
