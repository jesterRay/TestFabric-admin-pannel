@extends('layouts/contentNavbarLayout')

@section('title', 'New Survey')

@section('content')

<div class="row">
  <div class="col-12 mb-6">
    <div class="card mb-6">
        <div class="card-body pt-4">
          <div class="card-title text-primary h4 mb-6">Create Survey</div>
          <form method="POST" action='{{route('survey.save')}}'>
            @csrf
            <div class="row g-6">
              <div class="col-md-6">
                <label for="title" class="form-label">Survey Title</label>
                <input class="form-control" type="text" id="title" name="title" value="" />
              </div>
              <div class="col-12">
                <div class="row">
                  <div class="col-md-6">
                    <label for="available_from" class="form-label">Available From</label>
                    <input class="form-control" type="datetime-local" value="{{ now()->format('Y-m-d\TH:i') }}" id="available_from" name="available_from" />
                  </div>
                  <div class="col-md-6">
                    <label for="available_until" class="form-label">Available Until</label>
                    <input class="form-control" type="datetime-local" value="{{ now()->addMinutes(120)->format('Y-m-d\TH:i') }}" id="available_until" name="available_until"/>
                  </div>
                </div>
              </div>
              <div>
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" maxlength="500" name="description" id="description" rows="3" placeholder="Enter your descripiton..."></textarea>
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
