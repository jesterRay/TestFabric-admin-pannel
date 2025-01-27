@extends('layouts/contentNavbarLayout')

@section('title', 'Add Service')

@section('content')


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
          <div class="card-title text-primary h4 mb-6">Edit Test Method</div>
          <form 
            method="POST" 
            action='{{route('test-method.update',['id' => $testMethod->methods__ID])}}'  
            enctype="multipart/form-data"
            >
            @csrf
            @method('PUT')
            <div class="row g-6">
                <div class="col-md-6">
                    <label for="methods__Name" class="form-label">Menu Item Name *</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="methods__Name" 
                        name="methods__Name" 
                        value="{{ old('methods__Name') ?? $testMethod->methods__Name}}" 
                        required
                    />
                </div>
                <div class="col-md-6">
                    <label for="methods__Sequence" class="form-label">Order *</label>
                    <input 
                        class="form-control" 
                        type="number" 
                        id="methods__Sequence" 
                        name="methods__Sequence" 
                        value="{{ old('methods__Sequence') ?? $testMethod->methods__Sequence}}" 
                        required
                    />
                </div>
                
                <div class="col-md-6">
                    <x-select-two-drop-down
                        name="methods__Standard"
                        title="Standard *"
                        placeholder="Select Standard"
                        :options="$standards"
                        :selected="old('methods__Standard',$testMethod->methods__Standard)"
                        :multiple="false"

                    />
                </div>
                <div class="col-md-6">
                    <x-select-two-drop-down
                        name="methods__Status"
                        title="Select Status"
                        placeholder="Select Status"
                        :multiple="false"
                        :options="$statusOptions"
                        :selected="old('methods__Status', $testMethod->methods__Status)"
                    />
                </div>
                <div class="col-12">
                    <label for="methods__Description" class="form-label" required>Description *</label>
                    <textarea 
                        class="form-control" 
                        name="methods__Description" 
                        id="methods__Description" 
                        rows="3" required
                    >{{ old('methods__Description') ?? $testMethod->methods__Description}}</textarea>
                </div>

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