@extends('templates.main')

@section('content')

    @can('logged-in')
        <h1 class="display-3">Hi, {{ Auth::user()->name }} !</h1>

        <div class="card-group">
            <div class="card">
                <div class="card-body">
                    <div id="graph"></div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div id="graph2"></div>
                </div>
            </div>
        </div>
    @endcan

    @guest
        <h1>Hi Homepage</h1>

        <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
            <div class="card-header">Header</div>
            <div class="card-body">
                <h5 class="card-title">Primary card title</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            </div>
        </div>
        <div class="card text-white bg-info mb-3" style="max-width: 18rem;">
            <div class="card-header">Header</div>
            <div class="card-body">
                <h5 class="card-title">Info card title</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            </div>
        </div>
    @endguest

    <script type="application/javascript">
        document.addEventListener('DOMContentLoaded', function () {

            Highcharts.chart('graph', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Column chart with negative values'
                },
                xAxis: {
                    categories: ['Apples', 'Oranges', 'Pears', 'Grapes', 'Bananas']
                },
                credits: {
                    enabled: false
                },
                series: [{
                    name: 'John',
                    data: [5, 3, 4, 7, 2]
                }, {
                    name: 'Jane',
                    data: [2, -2, -3, 2, 1]
                }, {
                    name: 'Joe',
                    data: [3, 4, 4, -2, 5]
                }]
            });

            Highcharts.chart('graph2', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Column chart with negative values'
                },
                xAxis: {
                    categories: ['Apples', 'Oranges', 'Pears', 'Grapes', 'Bananas']
                },
                credits: {
                    enabled: false
                },
                series: [{
                    name: 'John',
                    data: [5, 3, 4, 7, 2]
                }, {
                    name: 'Jane',
                    data: [2, -2, -3, 2, 1]
                }, {
                    name: 'Joe',
                    data: [3, 4, 4, -2, 5]
                }]
            });
        });
    </script>
@endsection
