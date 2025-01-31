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
  <div class="col-lg-6 mb-6">
    <div class="card h-100">
      <div class="card-body">
        <h5 class="card-title text-primary mb-3">Country Details</h5>
            <canvas id="chartjs_bar"></canvas>
      </div>
    </div>
  </div>
  
  <div class="col-lg-6 mb-6">
    <div class="card h-100">
        <div class="card-body">
            <h5 class="card-title text-primary mb-3">Success Ratio</h5>
            <canvas id="pie-Chart"></canvas>

        </div>
    </div>
  </div>

  <div class="col-12 mb-6">
    <x-table 
        title="Summary"
        :thead="['#', 'QR', 'IP', 'Country','City', 'State', 'Location', 'Method', 'Date']" 
        :route="route('verification-history.index')"
        :columns='[
            ["data" => "DT_RowIndex", "name" => "DT_RowIndex","searchable" => false,"orderable" => false],
            ["data" => "QR_CODE", "name" => "QR_CODE"],
            ["data" => "IP", "name" => "IP"],
            ["data" => "country", "name" => "country"],
            ["data" => "city", "name" => "city"],
            ["data" => "state", "name" => "state"],
            ["data" => "location", "name" => "location"],
            ["data" => "method", "name" => "method"],
            ["data" => "date_time", "name" => "date_time"],
        ]'
    />

  </div>

  
  
</div>




</div>
@endsection



@push('script')
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script type="text/javascript">
        var ctx = document.getElementById("chartjs_bar").getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels:{!! json_encode($countries) !!},
                        datasets: [{
                            backgroundColor: [
                            "#f47b25",  // Orange
                            "#7673e6",  // Blue
                            "#b03cd5",  // Purple
                            "#ffc750",  // Yellow
                            "#2ec551",  // Green
                            "#7040fa",  // Indigo
                            "#ff004e",  // Red
                            "#00b3b3",  // Teal
                            "#ff6b6b",  // Coral
                            "#4a90e2",  // Sky Blue
                            "#50c878",  // Emerald
                            "#9b59b6",  // Violet
                            "#f39c12",  // Golden
                            "#34495e"   // Dark Blue Gray
                            ],
                            data:{!! json_encode($countryCountData) !!},
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
 
 
                }
                });


	    var ctx = document.getElementById("pie-Chart").getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels:["Success","Invalid"],
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
                            data:{!! json_encode($verificationCount) !!},
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
 
 
            }
    });
    </script>
@endpush