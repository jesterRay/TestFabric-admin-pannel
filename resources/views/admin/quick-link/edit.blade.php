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
          <div class="card-title text-primary h4 mb-6">Edit Quick Link</div>
          <form method="POST" action='{{route('quick-link.update',['id' => $link->links__ID ])}}'>
            @csrf
            @method("PUT")
            <div class="row g-6">
                <div class="col-md-6">
                    <label for="links__Title" class="form-label">Title *</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="links__Title" 
                        name="links__Title" 
                        value="{{old('links__Title',$link->links__Title)}}" 
                        required
                    />
                </div>
                <div class="col-md-6">
                    <label for="links__URL" class="form-label">Url *</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="links__URL" 
                        name="links__URL" 
                        value="{{old('links__URL',$link->links__URL)}}" 
                        required
                    />
                </div>
                <div class="col-md-6">
                    <label for="links__Sequence" class="form-label">Sequence *</label>
                    <input 
                        class="form-control" 
                        type="number" 
                        id="links__Sequence" 
                        name="links__Sequence" 
                        value="{{old('links__Sequence',$link->links__Sequence)}}" 
                        required
                    />
                </div>
                <div class="col-md-6">
                    <x-select-two-drop-down
                        name="links__Status"
                        title="Status"
                        placeholder="Select Status"
                        :options="$statusOptions"
                        :selected="old('links__Status',$link->links__Status)"
                        :multiple="false"

                    />
                </div>

            </div>
            <div class="mt-6">
              <button type="submit" class="btn btn-primary me-3">Add</button>
              <a href="{{ route('quick-link.index') }}" class="btn btn-secondary">Cancel</a>

            </div>


              
          </form>
        </div>
      </div>
  </div>
</div>

@endsection
