@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Page')

@section('content')

<div class="row">
    <div class="col-12 mb-6">
        <div class="card mb-6">
            <div class="card-body pt-4">
                <div class="card-title text-primary h4 mb-6">Edit Order Heading</div>
                <form method="POST" action="{{ route('order.heading.update', $heading->id ) }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-6 pt-6">
                        <!-- Heading 1 Name Field -->
                        <div class="row">
                            <div class="col-md-6">
                                <label for="heading_1" class="form-label">Heading For Quote *</label>
                                <input 
                                    class="form-control" 
                                    type="text" 
                                    id="heading_1" 
                                    name="heading_1" 
                                    value="{{ $heading->heading_1 }}" 
                                    required 
                                />
                            </div>
                        </div>

                        <!-- Heading 2 Name Field -->
                        <div class="col-md-6">
                            <label for="heading_2" class="form-label">Heading For Order *</label>
                            <input 
                                class="form-control" 
                                type="text" 
                                id="heading_2" 
                                name="heading_2" 
                                value="{{ $heading->heading_2 }}" 
                                required 
                            />
                        </div>

                    </div>
                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary me-3">Update</button>
                        {{-- <a href="{{ route('page.index') }}" class="btn btn-secondary">Cancel</a> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
