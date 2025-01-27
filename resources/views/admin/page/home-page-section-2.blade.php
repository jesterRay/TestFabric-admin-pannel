@extends('layouts/contentNavbarLayout')

@push('style')
    
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.css" />

@endpush

@section('title', 'Add Page')

@section('content')

<div class="row">
    <div class="col-12 mb-6">
        <div class="card mb-6">
            <div class="card-body pt-4">
                <div class="card-title text-primary h4 mb-6">Create Home Page Section 2</div>
                <form method="POST" action="{{ route('page.home.update',['id' => $page->id]) }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-6">
                        
                        <div class="col-md-6">
                            <label for="heading" class="form-label">Heading</label>
                            <input 
                                class="form-control" 
                                type="text" 
                                id="heading" 
                                name="heading" 
                                value="{{old('heading',$page->heading)}}" 
                            />
                        </div>

                        <!-- Long Content Field -->
                        <div class="col-md-12">
                            <label  for="ckEdit" class="form-label">Page Content *</label>
                            <textarea 
                                class="form-control" 
                                id="ckEdit" 
                                name="text" 
                                rows="20" 
                                required
                            >{{ old('text',$page->text)}}</textarea>
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
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ClassicEditor
                .create(document.querySelector('#ckEdit'), {
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
    </script>
@endpush