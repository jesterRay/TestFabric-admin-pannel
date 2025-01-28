@extends('layouts/contentNavbarLayout')

@section('title', 'Upload Related Document')

@section('content')

<div class="row">
  <div class="col-12 mb-6">
    <div class="card mb-6">
        <div class="card-body pt-4">
          <div class="card-title text-primary h4 mb-6">Upload Related Document</div>
          <form method="POST" action="{{ route('upload-related-document.save') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-6">
              <!-- Price Unit Name -->
              <div class="col-md-6">
                  <label for="file" class="form-label">File *</label>
                  <input 
                      class="form-control mb-1" 
                      type="file" 
                      id="file" 
                      name="file"
                      accept=".pdf, .ppt, .xlsx, .doc, .docx, .jpg, .jpeg, .txt"
                      required
                  />
                  <small>Please upload file in pdf, ppt, xlsx, doc, docx, jpg, or txt format only.</small>
              </div>

              <div class="row pt-6">
                <!-- File Product Dropdown -->
                <div class="col-md-6">
                  <x-select-two-drop-down
                      name="files__Product"
                      title="Product *"
                      placeholder="Select..."
                      :options="$products"
                      :selected="old('files__Product',$selectedProduct)"
                      :multiple="false"
                  />
                </div>
              </div>

              <!-- Product Description -->
              <div class="col-12">
                <label for="files__Description" class="form-label">File Description *</label>
                <textarea 
                    class="form-control" 
                    id="files__Description" 
                    name="files__Description" 
                    rows="4" 
                    required>{{ old('files__Description') }}</textarea>
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
