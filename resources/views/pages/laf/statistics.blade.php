@extends('layouts.app')

@section('template_title')
@endsection

@section('template_fastload_css')
@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">

                @include('panels.laf.statistics')

            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Status', 'Antall saker'],
                ['Registrert tapt (Etterlysning)', {{ $lost }}],
                ['Registrert mistet (Gjenstand)',      {{ $found }}],
                ['Kastet',  {{ $evicted }}],
                ['Sendt til politi', {{ $police }}],
                ['Venter på sending til politi',    {{ $wait_for_police }}],
                ['Avsluttet',  {{ $canceled }}],
                ['Venter på å bli utlevert',  {{ $wait_for_delivery }}],
                ['Venter på sending',  {{ $wait_for_send }}],
                ['Sendt',  {{ $sent }}],
                ['Venter på henting',  {{ $wait_for_pickup }}],
                ['Hentet',  {{ $picked_up }}],
            ]);

            var options = {
                title: 'Fordeling av statuser',
                pieHole: 0.3,
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
            chart.draw(data, options);
        }
    </script>
@endsection
