@extends('layouts/contentNavbarLayout')

@section('title', 'Add Service')

@section('content')

{{-- @dd('categories: ', $categories) --}}

@php
    $statusOptions = collect([
                        (object) ['id' => 'Show','name' => 'Show'],
                        (object) ['id' => 'Hide','name' => 'Hide'],
    ]);
@endphp

<div class="row">
  <div class="col-12 mb-6">
    <div class="card mb-6">
        <div class="card-body pt-4">
          <div class="card-title text-primary h4 mb-6">Create Continent</div>
          <form method="POST" action='{{route('continent.save')}}'  enctype="multipart/form-data">
            @csrf
            <div class="row g-6">
                <div class="row">
                    <div class="col-md-6">
                        <label for="map__Name" class="form-label">Name *</label>
                        <input 
                            class="form-control" 
                            type="text" 
                            id="map__Name" 
                            name="map__Name" 
                            value="{{old('map__Name')}}" 
                            required
                        />
                    </div> 
                </div>
                <div class="col-md-6">
                    <x-select-two-drop-down
                        name="map__Status"
                        title="Status"
                        placeholder="Select Status"
                        :options="$statusOptions"
                        :selected="old('map__Status')"
                        :multiple="false"

                    />
                </div>

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