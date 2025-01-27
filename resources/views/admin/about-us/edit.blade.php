@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')

@section('page-script')
@vite(['resources/assets/js/pages-account-settings-account.js'])
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card mb-6">
      <div class="card-body pt-4">
        <form 
          method="POST" 
          action="{{route('about-us.update',['id' => $aboutUs->associations_and_partners__ID])}}"
        >
          @csrf
          @method('PUT')
          <div class="row g-6">
            <div class="col-md-6">
              <label for="name" class="form-label">Name</label>
              <input 
                class="form-control" 
                type="text" id="name" 
                name="name" 
                value="{{ old('name') ?? $aboutUs->associations_and_partners__Name}}" 
                required
              />
            </div>
            <div class="col-12">
                <label for="description" class="form-label" required>Description</label>
                <textarea 
                  class="form-control" 
                  name="description" 
                  id="description" 
                  rows="26"
                >{{ old('description') ?? $aboutUs->associations_and_partners__Description}}</textarea>
            </div>

          </div>
          <div class="mt-6">
            <button type="submit" class="btn btn-primary me-3">Update</button>
            {{-- <button type="reset" class="btn btn-outline-secondary">Cancel</button> --}}
          </div>
        </form>
      </div>
      <!-- /Account -->
    </div>
    
  </div>
</div>
@endsection
