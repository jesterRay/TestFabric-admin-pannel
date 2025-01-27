@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Event')

@section('content')

<div class="row">
    <div class="col-12 mb-6">
        <div class="card mb-6">
            <div class="card-body pt-4">
                <div class="card-title text-primary h4 mb-6">Edit Event</div>
                <form method="POST" action="{{ route('event.update', $event->events__ID) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row g-6">
                        <!-- Name Field -->
                        <div class="col-md-6">
                            <label for="events__Name" class="form-label">Name *</label>
                            <input 
                                class="form-control" 
                                type="text" 
                                id="events__Name" 
                                name="events__Name" 
                                value="{{ old('events__Name', $event->events__Name) }}" 
                                required 
                            />
                        </div>

                        <!-- Abbreviation Field -->
                        <div class="col-md-6">
                            <label for="events__Abbriviation" class="form-label">Abbreviation *</label>
                            <input 
                                class="form-control" 
                                type="text" 
                                id="events__Abbriviation" 
                                name="events__Abbriviation" 
                                value="{{ old('events__Abbriviation', $event->events__Abbriviation) }}" 
                                required 
                            />
                        </div>

                        <!-- URL Field -->
                        <div class="col-md-6">
                            <label for="events__Url" class="form-label">URL *</label>
                            <input 
                                class="form-control" 
                                type="url" 
                                id="events__Url" 
                                name="events__Url" 
                                value="{{ old('events__Url', $event->events__Url) }}" 
                                required 
                            />
                        </div>

                        <!-- Description Field -->
                        <div class="col-md-12">
                            <label for="events__Description" class="form-label">Description *</label>
                            <textarea 
                                class="form-control" 
                                id="events__Description" 
                                name="events__Description" 
                                rows="4" 
                                required
                            >{{ old('events__Description', $event->events__Description) }}</textarea>
                        </div>

                        <!-- Image Field -->
                        <div class="row mt-4">
                            <div class="col-md-6 mb-4">
                                <label for="imgfile" class="form-label">Image</label>
                                <div class="my-3">
                                    <img 
                                        id="imgPreview" 
                                        src="{{ $event->imgfile ? asset($event->imgfile) : '#' }}" 
                                        alt="Event Image" 
                                        style="{{ $event->imgfile ? '' : 'display: none;' }} max-width: 100%; height: auto;" 
                                    />
                                </div>
                                <input 
                                    class="form-control" 
                                    type="file" 
                                    id="imgfile" 
                                    name="imgfile" 
                                    onchange="previewImage(event)"
                                >
                            </div>
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
