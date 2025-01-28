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
          <div class="card-title text-primary h4 mb-6">Edit Minimum Order Quantity</div>
          <form method="POST" action="{{ route('min-order-quantity.update',['id' => $minQuantity->Min__ID]) }}">
            @csrf
            @method("PUT")
            <div class="row g-6">
                <!-- Minimum Order Quantity Name -->
                <div class="col-md-6">
                    <label for="Min__Name" class="form-label">Name *</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="Min__Name" 
                        name="Min__Name" 
                        value="{{ old('Min__Name', $minQuantity->Min__Name) }}" 
                        required
                    />
                </div>


                <!-- Minimum Order Quantity Status Dropdown -->
                <div class="col-md-6">
                    <x-select-two-drop-down
                        name="Min__Status"
                        title="Status *"
                        placeholder="Select Status"
                        :options="$statusOptions"
                        :selected="old('Min__Status',$minQuantity->Min__Status)"
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
