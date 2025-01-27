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
        <form method="POST" action="{{route('service.update',['id' => $service->associations_and_partners__ID])}}">
          @csrf
          @method('PUT')
          <div class="row g-6">
            <div class="col-md-6">
              <label for="associations_and_partners__Name" class="form-label">Name</label>
              <input 
                class="form-control" 
                type="text" 
                id="associations_and_partners__Name" 
                name="associations_and_partners__Name" 
                value="{{ old('associations_and_partners__Name') ?? $service->associations_and_partners__Name}}" 
                required
              />
            </div>
            <div class="col-12">
                <label for="associations_and_partners__Description" class="form-label" required>Description</label>
                <textarea 
                  class="form-control" 
                  name="associations_and_partners__Description" 
                  id="associations_and_partners__Description" 
                  rows="26"
                >{{ old("associations_and_partners__Description") ?? $service->associations_and_partners__Description}}</textarea>
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
