@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Country Agent')

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
          <div class="card-title text-primary h4 mb-6">Edit Country Agent</div>
          <form 
            method="POST" 
            action="{{ route('country-agent.update', ['id' => $agent->agent__ID]) }}" 
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-6">
                <!-- Name -->
                <div class="col-md-6">
                    <label for="agent__Name" class="form-label">Name *</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="agent__Name" 
                        name="agent__Name" 
                        value="{{ old('agent__Name') ?? $agent->agent__Name }}" 
                        required
                    />
                </div>

                <!-- Email -->
                <div class="col-md-6">
                    <label for="agent__Email" class="form-label">Email *</label>
                    <input 
                        class="form-control" 
                        type="email" 
                        id="agent__Email" 
                        name="agent__Email" 
                        value="{{ old('agent__Email') ?? $agent->agent__Email }}" 
                        required
                    />
                </div>

                <!-- Password -->
                <div class="col-md-6">
                    <label for="agent__Password" class="form-label">Password</label>
                    <input 
                        class="form-control" 
                        type="password" 
                        id="agent__Password" 
                        name="agent__Password"
                        value="{{ old('agent__Password') ?? $agent->agent__Password }}"
                    />
                </div>

                <!-- Address -->
                <div class="col-12">
                    <label for="agent__Address" class="form-label">Address *</label>
                    <textarea 
                        class="form-control" 
                        id="agent__Address" 
                        name="agent__Address" 
                        rows="3" 
                        required>{{ old('agent__Address') ?? $agent->agent__Address }}</textarea>
                </div>

                <!-- Phone -->
                <div class="col-md-6">
                    <label for="agent__Phone" class="form-label">Phone *</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="agent__Phone" 
                        name="agent__Phone" 
                        value="{{ old('agent__Phone') ?? $agent->agent__Phone }}" 
                        required
                    />
                </div>

                <!-- Fax -->
                <div class="col-md-6">
                    <label for="agent__Fax" class="form-label">Fax</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        id="agent__Fax" 
                        name="agent__Fax" 
                        value="{{ old('agent__Fax') ?? $agent->agent__Fax }}"
                    />
                </div>

                <!-- Country -->
                <div class="col-md-6">
                    <x-select-two-drop-down
                        name="agent__Country"
                        title="Country *"
                        placeholder="Select Country"
                        :options="$countries"
                        :selected="old('agent__Country', $agent->agent__Country)"
                        :multiple="false"
                    />
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <x-select-two-drop-down
                        name="agent__Status"
                        title="Select Status *"
                        placeholder="Select Status"
                        :options="$statusOptions"
                        :selected="old('agent__Status', $agent->agent__Status)"
                        :multiple="false"
                    />
                </div>
            </div>

            <!-- Submit Button -->
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
        // Add any additional scripts if needed
    </script>
@endpush
