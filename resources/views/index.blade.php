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

        <!-- Modal -->
        <div class="modal fade" id="staticRMA" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Update RMA</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row text-center">
                                <div class="col-xl">
                                    <img id="myProdImg" src="" width="300" height="300">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl">
                                    <dl class="row">
                                        <dt class="col-sm-3">RMA No.</dt>
                                        <dd class="col-sm-9">
                                            <h5 id="rma_no"></h5>
                                        </dd>
                                        <dt class="col-sm-3">Current Status</dt>
                                        <dd class="col-sm-9">
                                            <h5 id="rma_status"></h5>
                                        </dd>
                                        <dt class="col-sm-3">Reason</dt>
                                        <dd class="col-sm-9">
                                            <h5 id="rma_reason"></h5>
                                        </dd>
                                        <dt class="col-sm-3">Update Status</dt>
                                        <dd class="col-sm-9">
                                            <div class="form-floating">
                                                <select class="form-select" id="floatingSelect" aria-label="Floating label select example" style="height: 60px">
                                                    <option value="Received">Received</option>
                                                    <option value="Received">Repair</option>
                                                    <option value="Received">Replacement 1-1</option>
                                                </select>
                                                <label for="floatingSelect">Select Status</label>
                                            </div>
                                        </dd>
                                        <dt class="col-sm-3">Receive At</dt>
                                        <dd class="col-sm-9">
                                            <div class="form-floating">
                                                <input type="date" class="form-control">
                                                <label for="date">Date Of Arrival</label>
                                            </div>
                                        </dd>
                                        <dt class="col-sm-3">Requested RMA At</dt>
                                        <dd class="col-sm-9">
                                            <h5 id="rma_request_at"></h5>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Understood</button>
                    </div>
                </div>
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
                                <a target="_blank" href="{{ route('rma.job-sheet', $rma->id) }}" class="btn btn-sm btn-primary">RMA Request Form</a>
                                <a href="/storage/rma/{{ $rma->file_path }}" target="_blank">
                                    <button class="btn"><i class="fa fa-download"></i> Download File</button>
                                </a>
                                <a
                                    href="#"
                                    data-myrmaid="{{ $rma->id }}"
                                    data-myprodpic="{{ $rma->product_image_path }}"
                                    data-myrmastatus="{{ $rma->status }}"
                                    data-myrmareason="{{ $rma->reason }}"
                                    data-myrmareqat="{{ date('d-M-Y H:i A', strtotime($rma->created_at)) }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#staticRMA">
                                    <button class="btn btn-sm btn-warning"><i data-feather="alert-triangle" class="feather-16" style="margin-bottom: 5px"></i> Update RMA Status</button>
                                </a>
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
        feather.replace();
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

        $('#staticRMA').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var rma_id = button.data('myrmaid') // Extract info from data-* attributes
            var prod_pic = button.data('myprodpic') // Extract info from data-* attributes
            var rma_status = button.data('myrmastatus') // Extract info from data-* attributes
            var rma_reason = button.data('myrmareason') // Extract info from data-* attributes
            var rma_request_at = button.data('myrmareqat') // Extract info from data-* attributes

            var modal = $(this)
            modal.find('.modal-body #rma_no').val(rma_id);
            modal.find('.modal-body #rma_status').val(rma_status);
            modal.find('.modal-body #rma_reason').val(rma_reason);
            modal.find('.modal-body #rma_request_at').val(rma_request_at);

            document.getElementById("rma_no").innerText = "#" + rma_id;
            document.getElementById("rma_status").innerText = rma_status;
            document.getElementById("rma_reason").innerText = rma_reason;
            document.getElementById("rma_request_at").innerText = rma_request_at;
            document.getElementById("myProdImg").src = "/storage/product/" + prod_pic;
        });
    </script>
@endsection
