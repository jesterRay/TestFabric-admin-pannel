@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Master Category')

@section('content')

{{-- @dd('category: ', $category) --}}

@php
    $statusOptions = collect([
        (object) ['id' => 'Show', 'name' => 'Show'],
        (object) ['id' => 'Hide', 'name' => 'Hide'],
    ]);
    $isEquipmentOptions = collect([
        (object) ['id' => '1', 'name' => 'Yes'],
        (object) ['id' => '0', 'name' => 'No'],
    ]);
@endphp



<div class="row">
  <div class="col-12 mb-6">
    <div class="card mb-6">
        <div class="card-body pt-4">
          <div class="card-title text-primary h4 mb-6">Edit Master Category</div>
          <form method="POST" action="{{ route('category.update', $category->category__ID) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-6">
                <!-- Category Name -->
                <div class="col-md-6">
                    <label for="category__Name" class="form-label">Category Name *</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="category__Name" 
                        name="category__Name" 
                        value="{{ old('category__Name', $category->category__Name) }}" 
                        required
                    />
                </div>

                <!-- Category Sequence -->
                <div class="col-md-6">
                    <label for="category__Sequence" class="form-label">Sequence *</label>
                    <input 
                        class="form-control" 
                        type="number" 
                        id="category__Sequence" 
                        name="category__Sequence" 
                        value="{{ old('category__Sequence', $category->category__Sequence) }}" 
                        required
                    />
                </div>

                <!-- Category Status Dropdown -->
                <div class="col-md-6">
                    <x-select-two-drop-down
                        name="category__Status"
                        title="Select Status"
                        placeholder="Select Status"
                        :options="$statusOptions"
                        :selected="old('category__Status', $category->category__Status)"
                        :multiple="false"
                    />
                </div>

                <!-- Is Equipment Radio Button -->
                <div class="row pt-6">
                    <div class="col-md-6">
                        <label for="isEquipment" class="form-label">Is Equipment?</label>
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="radio" 
                                name="isEquipment" 
                                id="isEquipmentYes" 
                                value="1" 
                                {{ old('isEquipment', $category->isEquipment) == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="isEquipmentYes">
                                Yes
                            </label>
                        </div>
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="radio" 
                                name="isEquipment" 
                                id="isEquipmentNo" 
                                value="0" 
                                {{ old('isEquipment', $category->isEquipment) == '0' ? 'checked' : '' }}>
                            <label class="form-check-label" for="isEquipmentNo">
                                No
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Category Image -->
                <div class="col-md-6 mb-4">
                    <label for="imgfile" class="form-label">Category Image</label>
                    <div class="my-3">
                        @if ($category->imgfile)
                            <img id="imgPreview" src="{{ asset($category->imgfile) }}" alt="Selected Image" style="max-width: 100%; height: auto;" />
                        @else
                            <img id="imgPreview" src="#" alt="Selected Image" style="display: none; max-width: 100%; height: auto;" />
                        @endif
                    </div>
                    <input class="form-control" type="file" id="imgfile" name="imgfile" onchange="previewImage(event)">
                </div>

                <!-- Category Banner -->
                <div class="col-md-6 mb-4">
                    <label for="category_banner" class="form-label">Category Banner</label>
                    <div class="my-3">
                        @if ($category->bannerfile)
                            <img id="bannerPreview" src="{{ asset($category->bannerfile) }}" alt="Selected Banner" style="max-width: 100%; height: auto;" />
                        @else
                            <img id="bannerPreview" src="#" alt="Selected Banner" style="display: none; max-width: 100%; height: auto;" />
                        @endif
                    </div>
                    <input class="form-control" type="file" id="category_banner" name="category_banner" onchange="previewBanner(event)">
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

@push('script')
    <script>
        // Image preview function
        function previewImage(event) {
            const fileInput = event.target;
            const file = fileInput.files[0];
            const preview = document.getElementById('imgPreview');

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result; // Set the image source to the file content
                    preview.style.display = 'block'; // Make the image visible
                };

                reader.readAsDataURL(file); // Read the file content
            } else {
                preview.src = "#"; // Reset the image source
                preview.style.display = 'none'; // Hide the image
            }
        }

        // Category banner preview function
        function previewBanner(event) {
            const fileInput = event.target;
            const file = fileInput.files[0];
            const preview = document.getElementById('bannerPreview');

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result; // Set the image source to the file content
                    preview.style.display = 'block'; // Make the image visible
                };

                reader.readAsDataURL(file); // Read the file content
            } else {
                preview.src = "#"; // Reset the image source
                preview.style.display = 'none'; // Hide the image
            }
        }
    </script>
@endpush
