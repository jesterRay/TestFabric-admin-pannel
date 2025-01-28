@extends('layouts/contentNavbarLayout')

@section('title', 'Add Available in')

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
          <div class="card-title text-primary h4 mb-6">Edit Available In</div>
          <form method="POST" action="{{ route('available-in.update',['id' => $availableIn->Available__ID]) }}">
            @csrf
            @method("PUT")
            <div class="row g-6">
                <!-- Category Name -->
                <div class="col-md-6">
                    <label for="Available__Name" class="form-label">Name *</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="Available__Name" 
                        name="Available__Name" 
                        value="{{ old('Available__Name', $availableIn->Available__Name) }}" 
                        required
                    />
                </div>


                <!-- Category Status Dropdown -->
                <div class="col-md-6">
                    <x-select-two-drop-down
                        name="Available__Status"
                        title="Status *"
                        placeholder="Select Status"
                        :options="$statusOptions"
                        :selected="old('Available__Status',$availableIn->Available__Status)"
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
