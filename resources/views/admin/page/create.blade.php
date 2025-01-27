@extends('layouts/contentNavbarLayout')

@section('title', 'Add Page')

@section('content')

<div class="row">
    <div class="col-12 mb-6">
        <div class="card mb-6">
            <div class="card-body pt-4">
                <div class="card-title text-primary h4 mb-6">Create Page</div>
                <form method="POST" action="{{ route('page.save') }}">
                    @csrf
                    <div class="row g-6">
                        <!-- Page Name Field -->
                        <div class="col-md-6">
                            <label for="page_name" class="form-label">Page Name *</label>
                            <input 
                                class="form-control" 
                                type="text" 
                                id="page_name" 
                                name="page_name" 
                                value="{{ old('page_name') }}" 
                                required 
                            />
                        </div>

                        <!-- Page Title Field -->
                        <div class="col-md-6">
                            <label for="page_title" class="form-label">Page Title *</label>
                            <input 
                                class="form-control" 
                                type="text" 
                                id="page_title" 
                                name="page_title" 
                                value="{{ old('page_title') }}" 
                                required 
                            />
                        </div>

                        <!-- Keywords Field -->
                        <div class="col-md-12">
                            <label for="page_keywords" class="form-label">Page Keywords *</label>
                            <textarea 
                                class="form-control" 
                                id="page_keywords" 
                                name="page_keywords" 
                                rows="4" 
                                required
                            >{{ old('page_keywords') }}</textarea>
                        </div>

                        <!-- Description Field -->
                        <div class="col-md-12">
                            <label for="page_description" class="form-label">Page Description *</label>
                            <textarea 
                                class="form-control" 
                                id="page_description" 
                                name="page_description" 
                                rows="4" 
                                required
                            >{{ old('page_description') }}</textarea>
                        </div>

                        <!-- Long Content Field -->
                        <div class="col-md-12">
                            <label for="page_long_content" class="form-label">Page Long Content *</label>
                            <textarea 
                                class="form-control" 
                                id="page_long_content" 
                                name="page_long_content" 
                                rows="20" 
                                required
                            >{{ old('page_long_content') }}</textarea>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary me-3">Add Page</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
