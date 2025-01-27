@extends('layouts/contentNavbarLayout')

@section('title', 'New Survey')

@section('content')

<div class="row">
  <div class="col-12 mb-6">
    <div class="card mb-6">
        <div class="card-body pt-4">
          <div class="card-title text-primary h4 mb-6">Edit Survey</div>
          <form method="POST" action="{{ route('survey.update', ['id' => $survey->id]) }}">
            @csrf
            @method('PUT')
            <div class="row g-6">
              <div class="col-md-6">
                <label for="title" class="form-label">Survey Title</label>
                <input class="form-control" type="text" id="title" name="title" value="{{$survey->title}}" />
              </div>
              <div class="col-12">
                <div class="row">
                  <div class="col-md-6">
                    <label for="available_from" class="form-label">Available From</label>
                    <input class="form-control" type="datetime-local" value="{{ $survey->available_from }}" id="available_from" name="available_from" />
                  </div>
                  <div class="col-md-6">
                    <label for="available_until" class="form-label">Available Until</label>
                    <input class="form-control" type="datetime-local" value="{{ $survey->available_until }}" id="available_until" name="available_until"/>
                  </div>
                </div>
              </div>
              <div>
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" maxlength="500" name="description" id="description" rows="3" placeholder="Enter your descripiton...">{{$survey->description}}</textarea>
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
