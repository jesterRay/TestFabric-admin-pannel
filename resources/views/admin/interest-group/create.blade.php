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
          <div class="card-title text-primary h4 mb-6">Create Interest Group</div>
          <form method="POST" action='{{route('interest-group.save')}}'  enctype="multipart/form-data">
            @csrf
            <div class="row g-6">
                <div class="col-md-6">
                    <label for="menu_Name" class="form-label">Menu Item Name *</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="menu_Name" 
                        name="menu_Name" 
                        value="{{old('menu_Name')}}" 
                        required
                    />
                </div>
                <div class="col-md-6">
                    <label for="menu_Order" class="form-label">Order *</label>
                    <input 
                        class="form-control" 
                        type="number" 
                        id="menu_Order" 
                        name="menu_Order" 
                        value="{{old('menu_Order')}}" 
                        required
                    />
                </div>
                <div class="col-md-6">
                    <x-select-two-drop-down
                        name="menu_Categories[]"
                        title="Menu Item Categories"
                        placeholder=""
                        :options="$categories"
                        :multiple="true"
                        :selected="old('menu_Categories')"
                    />
                </div>
                <div class="col-md-6">
                    <x-select-two-drop-down
                        name="menu_Status"
                        title="Select Status"
                        placeholder="Select Status"
                        :options="$statusOptions"
                        :selected="old('menu_Status')"
                        :multiple="false"

                    />
                </div>

                <div class="col-md-6 mb-4">
                    <label for="imgfile" class="form-label">Image</label>
                    <div class="my-3">
                        <img id="imgPreview" src="#" alt="Selected Image" style="display: none; max-width: 100%; height: auto;" />
                    </div>
                    <input class="form-control" type="file" id="imgfile" name="imgfile"  onchange="previewImage(event)">
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


@push('script')
    <script>
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
    </script>
@endpush