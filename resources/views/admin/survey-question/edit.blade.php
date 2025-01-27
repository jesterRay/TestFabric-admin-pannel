@extends('layouts/contentNavbarLayout')

@section('title', 'New Survey')

@section('content')

<div class="row">
  <div class="col-12 mb-6">
    <div class="card mb-6">
        <div class="card-body pt-4">
            <div class="d-flex flex-md-row flex-column justify-content-between align-items-center">
                <div class="card-title text-primary h4 mb-6">Add Survey Question</div>
                <div class="btn-group">
                    <button id="clear-form" class="btn btn-danger me-3">Clear</button>
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Primary</button>
                    <ul class="dropdown-menu">
                        <li>
                            <button class="dropdown-item" onclick="selectQuestionType('text')">Text</button></li>
                            <li><button class="dropdown-item" onclick="selectQuestionType('email')">Email</button></li>
                    <li><button class="dropdown-item" onclick="selectQuestionType('textarea')">textarea</button></li>
                    <li><button class="dropdown-item" onclick="selectQuestionType('number')">Number</button></li>
                    <li><button class="dropdown-item" onclick="selectQuestionType('rating')">Rating</button></li>
                    <li><button class="dropdown-item" onclick="selectQuestionType('date')">Date</button></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><button class="dropdown-item" onclick="selectQuestionType('radio')">Radio Button</button</li>
                    <li><button class="dropdown-item" onclick="selectQuestionType('checkbox')">Check Box</button</li>
                    </ul>
                </div>
            
          </div>
          <form method="POST" action='{{route('survey.question.update',['id' => $survey_id])}}'>
            @csrf
            @method('PUT')
            <div id="questions-wrapper"></div>
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
        function addQuestionForm(questionType) {
            const questionsWrapper = document.getElementById('questions-wrapper');
            const questionCount = document.querySelectorAll('.question-wrapper').length + 1;

            // Create a question wrapper div
            let questionWrapper = document.createElement('div');
            questionWrapper.classList.add('question-wrapper', 'mb-3');

            // Create a small text label to show the question type
            let fieldTypeLabel = document.createElement('small');
            fieldTypeLabel.classList.add('text-muted', 'd-block', 'mb-1');
            fieldTypeLabel.textContent = `Field Type: ${questionType}`;
            questionWrapper.appendChild(fieldTypeLabel);

            // Create a label for the question text input
            let questionLabelWrapper = document.createElement('div');
            questionLabelWrapper.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'mb-2');

            let questionLabel = document.createElement('label');
            questionLabel.classList.add('custom-form-input-title', 'mb-0');
            questionLabel.textContent = `Question ${questionCount}:`;
            
            // Add delete button with FA icon
            let deleteButton = document.createElement('button');
            deleteButton.type = 'button';
            deleteButton.classList.add('btn', 'btn-danger', 'btn-sm');
            deleteButton.innerHTML = '<i class="bx bx-trash"></i>'; // FontAwesome delete icon
            
            // Add event listener to remove the specific question wrapper on delete button click
            deleteButton.addEventListener('click', function() {
                questionWrapper.remove();
            });

            // Append the label and delete button to the label wrapper
            questionLabelWrapper.appendChild(questionLabel);
            questionLabelWrapper.appendChild(deleteButton);

            // Append the label wrapper to the question wrapper
            questionWrapper.appendChild(questionLabelWrapper);

            // Create the input field for the question text
            let questionInput = document.createElement('input');
            questionInput.type = 'text';
            questionInput.name = `question_${questionCount}_text`; // Unique name for this question
            questionInput.placeholder = 'Enter your question';
            questionInput.classList.add('form-control', 'mb-2');
            questionWrapper.appendChild(questionInput);

            // Create the required checkbox
            let requiredCheckboxWrapper = document.createElement('div');
            requiredCheckboxWrapper.classList.add('form-check', 'mb-2');
            
            let requiredCheckbox = document.createElement('input');
            requiredCheckbox.type = 'checkbox';
            requiredCheckbox.classList.add('form-check-input');
            requiredCheckbox.name = `question_${questionCount}_required`;
            requiredCheckbox.id = `required_${questionCount}`;
            
            let requiredLabel = document.createElement('label');
            requiredLabel.classList.add('form-check-label');
            requiredLabel.textContent = 'Required';
            requiredLabel.htmlFor = `required_${questionCount}`;
            
            requiredCheckboxWrapper.appendChild(requiredCheckbox);
            requiredCheckboxWrapper.appendChild(requiredLabel);
            questionWrapper.appendChild(requiredCheckboxWrapper);

            // Store the question type as a hidden field
            let questionTypeInput = document.createElement('input');
            questionTypeInput.type = 'hidden';
            questionTypeInput.name = `question_${questionCount}_type`;
            questionTypeInput.value = questionType;
            questionWrapper.appendChild(questionTypeInput);

            // Check if the question type is 'radio' or 'checkbox'
            if (questionType === 'radio' || questionType === 'checkbox') {
                // Create a button to add more options (for text only)
                let addOptionButton = document.createElement('button');
                addOptionButton.type = 'button';
                addOptionButton.classList.add('btn', 'btn-primary', 'mb-2');
                addOptionButton.textContent = 'Add Option';
                
                // Add event listener to dynamically add option text inputs
                addOptionButton.addEventListener('click', function() {
                    addOptionText(questionWrapper, questionCount);
                });

                questionWrapper.appendChild(addOptionButton);

                // Create an initial option by default
                addOptionText(questionWrapper, questionCount);
            }

            // Append the question wrapper to the main wrapper
            questionsWrapper.appendChild(questionWrapper);
        }

        // Function to dynamically add an option text input for radio/checkbox questions
        function addOptionText(questionWrapper, questionCount) {
            const optionCount = questionWrapper.querySelectorAll('.option-text-wrapper').length + 1;
            // console.log("Option Count: ",optionCount);
            // Create a wrapper div for the option text input
            let optionTextWrapper = document.createElement('div');
            optionTextWrapper.classList.add('option-text-wrapper', 'mb-2');

            // Create the input field where the user can input the option text
            let optionLabelInput = document.createElement('input');
            optionLabelInput.type = 'text';
            optionLabelInput.name = `question_${questionCount}_option_${optionCount}_text`;
            optionLabelInput.placeholder = `Option ${optionCount} value`;
            optionLabelInput.classList.add('form-control', 'mb-1', 'option-label-input');

            // Append the option text input to the option wrapper
            optionTextWrapper.appendChild(optionLabelInput);

            // Append the option wrapper to the question wrapper
            questionWrapper.appendChild(optionTextWrapper);
        }

        function selectQuestionType(type){
            addQuestionForm(type)
        }

        // Add an event listener to handle the clear form click event
        const clearFormButton = document.getElementById('clear-form');
        clearFormButton.addEventListener('click', function (e) {
            e.preventDefault();  // Prevent the default form submission behavior

            // Select the questions wrapper that contains all the dynamically added questions
            const questionsWrapper = document.getElementById('questions-wrapper');

            // Remove all child elements of the questions wrapper (i.e., all the added fields)
            while (questionsWrapper.firstChild) {
                questionsWrapper.removeChild(questionsWrapper.firstChild);
            }
        });

        // Function to display questions dynamically with delete functionality
        function displayQuestions(questions) {
            const questionsWrapper = document.getElementById('questions-wrapper');

            questions.forEach((question, index) => {
                // Create a wrapper div for the question
                let questionWrapper = document.createElement('div');
                questionWrapper.classList.add('question-wrapper', 'mb-3');

                // Create a small text label to show the question type
                let fieldTypeLabel = document.createElement('small');
                fieldTypeLabel.classList.add('text-muted', 'd-block', 'mb-1');
                fieldTypeLabel.textContent = `Field Type: ${question.question_type}`;
                questionWrapper.appendChild(fieldTypeLabel);

                // Create a label wrapper for the question text and delete button
                let questionLabelWrapper = document.createElement('div');
                questionLabelWrapper.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'mb-2');

                // Create a label for the question text
                let questionLabel = document.createElement('label');
                questionLabel.classList.add('custom-form-input-title', 'mb-0');
                questionLabel.textContent = `Question ${index + 1}: ${question.question_text}`;

                // Add delete button with FA icon
                let deleteButton = document.createElement('button');
                deleteButton.type = 'button';
                deleteButton.classList.add('btn', 'btn-danger', 'btn-sm');
                deleteButton.innerHTML = '<i class="bx bx-trash"></i>'; // FontAwesome delete icon

                // Add event listener to remove the specific question wrapper on delete button click
                deleteButton.addEventListener('click', function() {
                    questionWrapper.remove();
                });

                // Append the label and delete button to the label wrapper
                questionLabelWrapper.appendChild(questionLabel);
                questionLabelWrapper.appendChild(deleteButton);

                // Append the label wrapper to the question wrapper
                questionWrapper.appendChild(questionLabelWrapper);

                // Create the input field for the question text
                let questionInput = document.createElement('input');
                questionInput.type = 'text';
                questionInput.name = `question_${index + 1}_text`; // Unique name for this question
                questionInput.value = question.question_text; // Populate with the existing question text
                questionInput.placeholder = 'Enter your question';
                questionInput.classList.add('form-control', 'mb-2');
                questionWrapper.appendChild(questionInput);

                // Create the required checkbox
                let requiredCheckboxWrapper = document.createElement('div');
                requiredCheckboxWrapper.classList.add('form-check', 'mb-2');
                
                let requiredCheckbox = document.createElement('input');
                requiredCheckbox.type = 'checkbox';
                requiredCheckbox.classList.add('form-check-input');
                requiredCheckbox.name = `question_${index + 1}_required`;
                requiredCheckbox.id = `required_${index + 1}`;
                requiredCheckbox.checked = question.question_is_required === 1; // Set checked based on the existing value
                
                let requiredLabel = document.createElement('label');
                requiredLabel.classList.add('form-check-label');
                requiredLabel.textContent = 'Required';
                requiredLabel.htmlFor = `required_${index + 1}`;
                
                requiredCheckboxWrapper.appendChild(requiredCheckbox);
                requiredCheckboxWrapper.appendChild(requiredLabel);
                questionWrapper.appendChild(requiredCheckboxWrapper);

                // Store the question type as a hidden field
                let questionTypeInput = document.createElement('input');
                questionTypeInput.type = 'hidden';
                questionTypeInput.name = `question_${index + 1}_type`;
                questionTypeInput.value = question.question_type;
                questionWrapper.appendChild(questionTypeInput);

                // Check if the question type is 'radio' or 'checkbox'
                if (question.question_type === 'radio' || question.question_type === 'checkbox') {
                    // Create initial options
                    question.options.forEach((option, optionIndex) => {
                        displayOptionText(questionWrapper, index + 1, option, optionIndex + 1);
                    });

                    // Create a button to add more options
                    let addOptionButton = document.createElement('button');
                    addOptionButton.type = 'button';
                    addOptionButton.classList.add('btn', 'btn-primary', 'mb-2');
                    addOptionButton.textContent = 'Add Option';
                    
                    // Add event listener to dynamically add option text inputs
                    addOptionButton.addEventListener('click', function() {
                        addOptionText(questionWrapper, index + 1);
                    });

                    questionWrapper.appendChild(addOptionButton);
                }

                // Append the question wrapper to the main wrapper
                questionsWrapper.appendChild(questionWrapper);
            });
        }

        // Function to dynamically add an option text input for radio/checkbox questions
        function displayOptionText(questionWrapper, questionCount, existingOptionText = '', optionCount) {
            const optionTextWrapper = document.createElement('div');
            optionTextWrapper.classList.add('option-text-wrapper', 'mb-2');

            // Create the input field where the user can input the option text
            let optionLabelInput = document.createElement('input');
            optionLabelInput.type = 'text';
            optionLabelInput.name = `question_${questionCount}_option_${optionCount}_text`;
            optionLabelInput.placeholder = `Option ${optionCount} value`;
            optionLabelInput.classList.add('form-control', 'mb-1', 'option-label-input');
            optionLabelInput.value = existingOptionText; // Set existing option text if provided

            // Append the option text input to the option wrapper
            optionTextWrapper.appendChild(optionLabelInput);

            // Append the option wrapper to the question wrapper
            questionWrapper.appendChild(optionTextWrapper);
        }

        // call the displayQuestions function to display the questions
        const questions_temp = {!! json_encode($questions) !!}
        console.log(questions_temp)
        displayQuestions(questions_temp);

    </script>
@endpush