@extends('templates.main')

@section('content')

    <style>
        .btn-outline-dark {
            border-radius: 35px;
            font-size: 10px;
            box-shadow: none
        }

        .col-md-3 {
            margin-top: 5px
        }
    </style>

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

        <div class="container mt-4">
            <div class="row mt-2 g-1">
                @foreach($jobInfo as $ji)
                <div class="col-md-3">
                    <div class="card p-2" style="border: none">
                        <div class="text-right badge bg-warning"> <small class="lead">{{ $ji->job_type }}</small> </div>
                        <div class="text-center mt-2 p-3"> <img src="/image/XT-logo.png" width="100" height="65" /> <span class="d-block font-weight-bold">{{ $ji->job_name }}</span>
                            <hr> <span>Xmiryna Tech</span>
                            <div class="d-flex flex-row align-items-center justify-content-center"> <i class="fa fa-map-marker"></i> <small class="mx-1">{{ $ji->job_location }}</small> </div>
                            <div class="d-flex justify-content-between mt-3"> <span>RM {{ $ji->job_salary }}</span> <a href="{{ $ji->id }}"><button class="btn btn-sm btn-outline-dark">Apply Now</button></a> </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <h1>My RMA Requests</h1>
        <div class="card mt-3">
            <div class="card-body">
                <!-- START HERE -->
                @foreach($rmaInfo as $rma)
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="p-3 d-flex align-items-center">
                            <div class="mr-3">
                                <img src="/storage/product/{{ $rma->product_image_path }}" width="100" height="100" />
                            </div>

                            <div class="mx-3">
                                <h5>{{ $rma->product_name }}</h5>
                                <div class="text-muted monospace">
                                    {{ $rma->product_sn }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 d-flex align-items-center justify-content-center">
                        <div class="text-center">
                            <div>
                                Serial Number
                            </div>
                            <div class="monospace text-primary">
                                {{ $rma->sn_no }}
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 d-flex align-items-center justify-content-center">
                        <div class="text-center">
                            <div>
                                Status
                            </div>
                            <div class="text-primary">
                                {{ $rma->status }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-2 d-flex align-items-center justify-content-center">
                        <div class="p-3 text-center">
                            <div class="text-primary monospace">
                                <a href="https://xmiryna.com.my/rma/{{ $rma->id }}/pdf" class="btn btn-sm btn-primary">Print RMA Request Form</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                <!-- END HERE -->

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
