@extends('layouts/commonMaster' )
@include('layouts/sections/scripts')
@php
/* Display elements */
$contentNavbar = true;
$containerNav = ($containerNav ?? 'container-xxl');
$isNavbar = ($isNavbar ?? true);
$isMenu = ($isMenu ?? true);
$isFlex = ($isFlex ?? false);
$isFooter = ($isFooter ?? true);

/* HTML Classes */
$navbarDetached = 'navbar-detached';

/* Content classes */
$container = ($container ?? 'container-xxl');

@endphp

@section('layoutContent')
<div class="layout-wrapper py-4  layout-content-navbar {{ $isMenu ? '' : 'layout-without-menu' }}">
    <div class="layout-container d-flex justify-content-center align-items-center">
        <!-- Layout page -->
        <div class="content-wrapper response-card card">
            <div class="card-header d-flex flex-column justify-content-between align-items-center">
                <h4 class="text-primary ">{{$survey->title}}</h4>
                <p class="">{{$survey->description}}</p>
            </div>
            <div class="card-body">
                @php
                    // Determine the form action based on preview mode
                    $formAction = session('preview') ? '' : route('survey.response.save', ['id' => $survey->id]);
                @endphp
                <form id="add-question-form" class="response-form form-inline" method="POST" 
                    action="{{ $formAction }}">
                    @csrf
                    <div id="questions-wrapper">
                        @foreach ($questions as $index => $question)
                            <div class="question-wrapper mb-3 row">
                                <div class="question-title mb-1">
                                    <label class="custom-form-input-title">Question {{ $index + 1 }}:</label>
                                    <label class="custom-form-input-title">{{ $question['question_text'] }}</label>
                                </div>
                
                                <!-- Use question ID directly for the name attribute -->
                                @if ($question['question_type'] === 'radio')
                                    @foreach ($question['options'] as $option)
                                        <div class="form-check ms-4">
                                            <input 
                                                type="radio" 
                                                name="{{ $question['id'] }}"
                                                value="{{ $option }}" 
                                                class="form-check-input" 
                                                {{ $question['is_required'] ? 'required' : '' }}
                                            />
                                            <label class="form-check-label">{{ $option }}</label>
                                        </div>
                                    @endforeach
                                @elseif ($question['question_type'] === 'checkbox')
                                    @foreach ($question['options'] as $option)
                                        <div class="form-check  ms-4">
                                            <input 
                                                type="checkbox" 
                                                name="{{ $question['id'] }}[]"
                                                value="{{ $option }}" 
                                                class="form-check-input" 
                                            />
                                            <label class="form-check-label">{{ $option }}</label>
                                        </div>
                                    @endforeach
                                @elseif ($question['question_type'] === 'textarea')
                                    <textarea 
                                        name="{{ $question['id'] }}"
                                        placeholder="Enter your answer" 
                                        class="form-control mb-2" 
                                        rows="10" 
                                        cols="40"
                                        {{ $question['is_required'] ? 'required' : '' }}
                                    ></textarea>
                                @elseif ($question['question_type'] === 'rating')
                                    <input 
                                        type="number" 
                                        name="{{ $question['id'] }}"
                                        min="0" 
                                        max="5" 
                                        placeholder="Rate from 0 to 5" 
                                        class="form-control mb-2" 
                                        {{ $question['is_required'] ? 'required' : '' }}
                                    />
                                @else
                                    <input 
                                        type="{{ $question['question_type'] }}" 
                                        name="{{ $question['id'] }}"
                                        placeholder="Enter your answer" 
                                        class="form-control mb-2" 
                                        {{ $question['is_required'] ? 'required' : '' }}
                                    />
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="text-warning">
                        @if (isset($error))
                            {{ $error }}
                        @endif
                    </div>
                    
                    <div class="d-flex justify-content-between mt-3">
                        @if (!session('preview'))
                            <button type="submit" class="btn btn-primary">Submit Response</button>
                        @elseif (session('preview'))
                            <p class="text-warning">You are in preview mode can't submit the response</p>
                        @endif
                    </div>
                </form>
            </div>
            
        
        <!--/ Content wrapper -->
        </div>
    </div>
</div>
  <!-- / Layout wrapper -->
@endsection
