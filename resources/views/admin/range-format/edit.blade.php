@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Range Format')

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
          <div class="card-title text-primary h4 mb-6">Edit Range Format</div>
          <form method="POST" action="{{ route('range-format.update',['id' => $rangeformat->rangeformat__ID]) }}">
            @csrf
            @method("PUT")
            <div class="row g-6">
                <!-- Range Format Name -->
                <div class="col-md-6">
                    <label for="rangeformat__Name" class="form-label">Name *</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="rangeformat__Name" 
                        name="rangeformat__Name" 
                        value="{{ old('rangeformat__Name', $rangeformat->rangeformat__Name) }}" 
                        required
                    />
                </div>
                
                
                <!-- Range Format Sequence -->
                <div class="col-md-6">
                    <label for="rangeformat__Sequence" class="form-label">Sequence *</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="rangeformat__Sequence" 
                        name="rangeformat__Sequence" 
                        value="{{ old('rangeformat__Sequence', $rangeformat->rangeformat__Sequence) }}" 
                        required
                    />
                </div>


                <!-- Range Format Status Dropdown -->
                <div class="col-md-6">
                    <x-select-two-drop-down
                        name="rabgeformat__Status"
                        title="Status *"
                        placeholder="Select Status"
                        :options="$statusOptions"
                        :selected="old('rabgeformat__Status',$rangeformat->rabgeformat__Status)"
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
