@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('vendor-style')
@vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
@vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
@vite('resources/assets/js/dashboards-analytics.js')
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4>Select question to see Analytics</h4>
                </div>
                <div class="card-body">
                    <form id="question-form">
                        @if (!empty($responses))
                            @foreach ($responses as $key => $question)
                                @if (in_array($question['question_type'], ['radio', 'checkbox']))
                                    <label>
                                        <input type="radio" 
                                               name="question_id" 
                                               value="{{ $question['question_id'] }}" 
                                               onclick="handleQuestionSelect({{ $question['question_id'] }})">
                                        {{ $question['question_text'] }}
                                    </label><br>
                                @endif
                            @endforeach
                        @else
                            <p>No responses available.</p>
                        @endif
                    </form>                    
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6 col-sm-12 col-sm-12">
            <div class="card ">
                <div class="card-header ">
                    <h4>Success Ratio</h4>
                     
                </div>
                <div class="card-body">
                    <canvas id="pie-Chart" class="chartjs-render-monitor chart-dropshadow"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection



@push('script')
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script>
        // Function to calculate the percentage
        function calculatePercentage(labels, answers) {
            // Initialize an object to hold the count of each label
            const labelCounts = {};
            labels.forEach(label => {
                labelCounts[label] = 0; // Initialize each label count to 0
            });
            const totalAnswers = answers.length;
            // Count the occurrences of each label in the answers
            answers.forEach(answer => {
                if (labelCounts.hasOwnProperty(answer)) {
                    labelCounts[answer]++;
                }
            });
            // Calculate the percentage for each label
            const percentages = labels.map(label => {
                return totalAnswers > 0 ? (labelCounts[label] / totalAnswers) * 100 : 0; // Avoid division by zero
            });

            return percentages;
        }

        function calculatePercentageForRatings(ratings, ranges) {
            // Initialize an array to hold the count for each range
            const rangeCounts = new Array(ranges.length).fill(0); // Array of zeros for each range
            const totalRatings = ratings.length;

            // Count how many ratings fall into each range
            ratings.forEach(rating => {
                ranges.forEach((range, index) => {
                    const [min, max] = range; // Destructure range into min and max
                    if (rating >= min && rating < max) {
                        rangeCounts[index]++;
                    }
                });
            });

            // Calculate the percentage for each range
            const percentages = rangeCounts.map(count => {
                return totalRatings > 0 ? (count / totalRatings) * 100 : 0; // Avoid division by zero
            });

            return percentages;
        }


        // Function to get responses for a specific question ID
        function getQuestionResponsesById(responses, questionId) {
            return responses.find(response => response.question_id === questionId) || null;
        }
        
        let responses = {!! json_encode($responses); !!};
        console.log(responses);
        
        function handleQuestionSelect(questionId) {
            // Retrieve the question responses using the provided questionId
            let questions = getQuestionResponsesById(responses, questionId); // Note the corrected spelling of 'responses'

            // Ensure questions is not null
            if (questions) {
                // Map the options and answers correctly
                let labels = questions.options; // Assuming options is already an array of strings
                let answers = questions.answers; // Assuming answers is already an array of strings
                
                let percentages = null;
                if(questions.type === "rating") {
                    const ranges = [[0, 2], [2, 4], [4, 5]];
                    percentages = calculatePercentageForRatings(answers,ranges);
                }
                else{
                    percentages = calculatePercentage(labels, answers);
                }

                // Ensure the canvas context is set up correctly
                var ctx = document.getElementById("pie-Chart").getContext('2d');

                // Create or update the chart
                var myChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            backgroundColor: [
                                "#f47b25",
                                "#7673e6",
                                "#b03cd5",
                                "#ffc750",
                                "#2ec551",
                                "#7040fa",
                                "#ff004e"
                            ],
                            data: percentages,
                        }]
                    },
                    options: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                fontColor: '#71748d',
                                fontFamily: 'Circular Std Book',
                                fontSize: 14,
                            }
                        },
                        tooltips: {
                            enabled: true // Disable default tooltips
                        },
                        plugins: {
                            datalabels: {
                                formatter: (value, ctx) => {
                                    let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    let percentage = ((value * 100) / sum).toFixed(2) + "%"; // Calculate percentage
                                    return percentage;
                                },
                                color: '#fff',
                            }
                        }
                    }
                });
            } else {
                console.error("No data found for question ID:", questionId); // Error handling
            }
        }
    </script>
@endpush