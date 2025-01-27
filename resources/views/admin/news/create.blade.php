@extends('layouts/contentNavbarLayout')

@section('title', 'Add News')

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
          <div class="card-title text-primary h4 mb-6">Create News</div>
          <form method="POST" action='{{route('news.save')}}'  enctype="multipart/form-data">
            @csrf
            <div class="row g-6">
                <div class="col-md-6">
                    <label for="news__Title" class="form-label">Title *</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="news__Title" 
                        name="news__Title" 
                        value="{{old('news__Title')}}" 
                        required
                    />
                </div>
                <div class="col-md-6">
                    <label for="news__Date" class="form-label">Date *</label>
                    <input 
                        class="form-control" 
                        type="date" 
                        id="news__Date" 
                        name="news__Date" 
                        value="{{old('news__Date')}}" 
                        required
                    />
                </div>
                <div class="col-md-6">
                    <x-select-two-drop-down
                        name="news__Status"
                        title="Select Status"
                        placeholder="Select Status"
                        :options="$statusOptions"
                        :selected="old('news__Status')"
                        :multiple="false"
                    />
                </div>

                <div class="col-md-12 mt-3">
                    <label for="news__Short_Description" class="form-label">Short Description *</label>
                    <textarea 
                        class="form-control ckEdit" 
                        id="news__Short_Description" 
                        name="news__Short_Description" 
                        rows="4" >{{ old('news__Short_Description') }}</textarea>
                </div>

                <div class="col-md-12 mt-3">
                    <label for="news__Long_Description" class="form-label">Long Description *</label>
                    <textarea 
                        class="form-control ckEdit" 
                        id="news__Long_Description" 
                        name="news__Long_Description" 
                        rows="6">{{ old('news__Long_Description') }}</textarea>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6 mb-4">
                        <label for="imgfile" class="form-label">Image</label>
                        <div class="my-3">
                            <img id="imgPreview" src="#" alt="Selected Image" style="display: none; max-width: 100%; height: auto;" />
                        </div>
                        <input class="form-control" type="file" id="imgfile" name="imgfile"  onchange="previewImage(event)">
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


@push('script')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.ckEdit').forEach((element) => {
                ClassicEditor
                    .create(element, {
                        toolbar: {
                            items: [
                                'undo', 'redo', '|',
                                'heading', '|',
                                'bold', 'italic', '|',
                                'bulletedList', 'numberedList', '|',
                                'outdent', 'indent', '|',
                                'link', '|',
                                'blockQuote'
                            ]
                        }
                    })
                    .catch(error => {
                        console.error('Editor initialization failed', error);
                    });
            });

        });

        // preview funciton
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
