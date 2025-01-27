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
          <div class="card-title text-primary h4 mb-6">Create Standards</div>
          <form method="POST" action='{{route('tpvs.serial.save')}}'>
            @csrf
            <div class="row g-6">
                {{-- start range --}}
                <div class="row my-6">
                    <div class="col-md-6">
                        <label for="start" class="form-label">Start Range *</label>
                        <input 
                            class="form-control" 
                            type="number" 
                            id="start" 
                            name="start" 
                            value="{{old('start')}}" 
                            required
                        />
                    </div>
                </div>
                {{-- end range --}}
                <div class="row">
                    <div class="col-md-6">
                        <label for="end" class="form-label">End Range *</label>
                        <input 
                            class="form-control" 
                            type="number" 
                            id="end" 
                            name="end" 
                            value="{{old('end')}}" 
                            required
                        />
                    </div>
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
