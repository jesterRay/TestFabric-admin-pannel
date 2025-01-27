@extends('layouts/contentNavbarLayout')

@section('title', 'Add About Us')

@section('content')

<div class="row">
  <div class="col-12 mb-6">
    <div class="card mb-6">
        <div class="card-body pt-4">
          <div class="card-title text-primary h4 mb-6">Create About Us</div>
          <form method="POST" action='{{route('about-us.save')}}'>
            @csrf
            <div class="row g-6">
              <div class="col-md-6">
                <label for="name" class="form-label">Name *</label>
                <input class="form-control" type="text" id="name" name="name" value="{{ old('name') }}" required/>
              </div>
              <div class="col-md-6">
                <label for="abbrevation" class="form-label">Abbrevation *</label>
                <input class="form-control" type="text" id="abbrevation" name="abbrevation" value="{{ old('abbrevation') }}" required/>
              </div>
              <div class="col-12">
                <label for="description" class="form-label" required>Description *</label>
                <textarea class="form-control" name="description" id="description" rows="3" required>{{ old('description') }}</textarea>
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
