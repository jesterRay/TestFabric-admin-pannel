@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Subcategory')

@section('content')


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
          <div class="card-title text-primary h4 mb-6">Edit Subcategory</div>
          <form method="POST" action="{{ route('sub-category.update', $subcategory->subcategory__ID ) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- This ensures the form uses the PUT method for updating -->
            <div class="row g-6">
                <!-- Subcategory Name -->
                <div class="col-md-6">
                    <label for="subcategory__Name" class="form-label">Subcategory Name *</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="subcategory__Name" 
                        name="subcategory__Name" 
                        value="{{ old('subcategory__Name', $subcategory->subcategory__Name) }}" 
                        required
                    />
                </div>

                <!-- Parent Category Dropdown -->
                <div class="col-md-6">
                    <label for="subcategory__Category_Name" class="form-label">Category *</label>
                    <select 
                        class="form-control" 
                        id="subcategory__Category_Name" 
                        name="subcategory__Category_Name"
                        required>
                        <option value="" disabled>Select Parent Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('subcategory__Category_Name', $subcategory->subcategory__Category_Name) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Subcategory Sequence -->
                <div class="col-md-6">
                    <label for="subcategory__Sequence" class="form-label">Sequence *</label>
                    <input 
                        class="form-control" 
                        type="number" 
                        id="subcategory__Sequence" 
                        name="subcategory__Sequence" 
                        value="{{ old('subcategory__Sequence', $subcategory->subcategory__Sequence) }}" 
                        required
                    />
                </div>

                <!-- Subcategory Status -->
                <div class="col-md-6">
                    <x-select-two-drop-down
                        name="subcategory__Status"
                        title="Select Status"
                        placeholder="Select Status"
                        :options="$statusOptions"
                        :selected="old('subcategory__Status', $subcategory->subcategory__Status)"
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
                                {{ old('isEquipment', $subcategory->isEquipment) == '1' ? 'checked' : '' }}>
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
                                {{ old('isEquipment', $subcategory->isEquipment) == '0' ? 'checked' : '' }}>
                            <label class="form-check-label" for="isEquipmentNo">
                                No
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Subcategory Image -->
                <div class="col-md-6">
                    <label for="subcategory__image" class="form-label">Subcategory Image</label>
                    <div class="my-3">
                        @if($subcategory->subcategory__image)
                            <img id="imgPreview" src="{{ asset('subcat_images/' . suCrypt($subcategory->subcategory__ID) . '.jpg') }}" alt="Selected Image" style="max-width: 100%; height: auto;" />
                        @else
                            <img id="imgPreview" src="#" alt="Selected Image" style="display: none; max-width: 100%; height: auto;" />
                        @endif
                    </div>
                    <input 
                        class="form-control" 
                        type="file" 
                        id="subcategory__image" 
                        name="subcategory__image"
                        accept=".jpg"
                        onchange="previewImage(event)"
                    />
                </div>

                <!-- Banner Image -->
                <div class="col-md-6">
                    <label for="banner__image" class="form-label">Banner Image</label>
                    <div class="my-3">
                        @if($subcategory->banner__image)
                            <img id="bannerPreview" src="{{ asset('subcat_images/banner_' . suCrypt($subcategory->subcategory__ID) . '.jpg') }}" alt="Selected Banner" style="max-width: 100%; height: auto;" />
                        @else
                            <img id="bannerPreview" src="#" alt="Selected Banner" style="display: none; max-width: 100%; height: auto;" />
                        @endif
                    </div>
                    <input 
                        class="form-control" 
                        type="file" 
                        id="banner__image" 
                        name="banner__image"
                        accept=".jpg"
                        onchange="previewBanner(event)"
                    />
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
        // Image preview function for subcategory image
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
