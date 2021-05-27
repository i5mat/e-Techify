@extends('templates.main')

@section('content')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/css/ol.css"
          type="text/css">
    <style>
        .btn-outline-dark {
            border-radius: 35px;
            font-size: 10px;
            box-shadow: none
        }

        .btn-outline-primary {
            border-radius: 35px;
            font-size: 10px;
            box-shadow: none
        }

        .col-md-3 {
            margin-top: 5px
        }

        .map {
            height: 400px;
            width: 100%;
        }
    </style>

    @can('logged-in')
        <h1 class="display-3">Hi, {{ Auth::user()->name }} !</h1>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body border-3 border-start border-primary shadow h-100">
                        <div class="row g-0 align-items-center">
                            <div class="col" style="margin-right: 2px">
                                <div class="text-sm text-primary text-uppercase mb-1" style="font-weight: bold">
                                    Earnings Monthly
                                </div>
                                <div class="h5 mb-0" style="font-weight: bold">18</div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-calendar fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body border-3 border-start border-success shadow h-100">
                        <div class="row g-0 align-items-center">
                            <div class="col" style="margin-right: 2px">
                                <div class="text-sm text-success text-uppercase mb-1" style="font-weight: bold">
                                    Earnings Annual
                                </div>
                                <div class="h5 mb-0" style="font-weight: bold">18</div>
                            </div>
                            <div class="col-auto">
                                <i data-feather="dollar-sign" class="feather-32"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body border-3 border-start border-warning shadow h-100">
                        <div class="row g-0 align-items-center">
                            <div class="col" style="margin-right: 2px">
                                <div class="text-sm text-warning text-uppercase mb-1" style="font-weight: bold">
                                    Pending Requests
                                </div>
                                <div class="h5 mb-0" style="font-weight: bold">18</div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-comments fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 10px">
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0" style="font-weight: bold">Earnings Overview</h6>
                    </div>
                    <div class="card-body border-3 border-bottom border-warning">
                        <div id="graph"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0" style="font-weight: bold">Revenue Sources (Distributor)</h6>
                    </div>
                    <div class="card-body pt-4 pb-2 border-3 border-bottom border-warning">
                        <div id="graph2"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-11">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0" style="font-weight: bold">Job Offerings</h6>
                    </div>
                    <div class="card-body border-3 border-bottom border-warning">
                        <div class="row g-1">
                            @foreach($jobInfo as $ji)
                                <div class="col-md-3">
                                    <div class="card p-2" style="border: none">
                                        <div class="text-right badge bg-warning"><small
                                                class="lead">{{ $ji->job_type }}</small></div>
                                        <div class="text-center mt-2 p-3"><img src="/image/XT-logo.png" width="100"
                                                                               height="65"/> <span
                                                class="d-block font-weight-bold">{{ $ji->job_name }}</span>
                                            <span @if($ji->status == 'Not Occupied') class="badge bg-primary mt-2"
                                                  @else class="badge bg-success mt-2"
                                                  @endif style="color: white">{{ $ji->status }}</span>
                                            <hr>
                                            <span>{{ $ji->name }}</span>
                                            <div class="d-flex flex-row align-items-center justify-content-center"><i
                                                    class="fa fa-map-marker"></i> <small
                                                    class="mx-1">{{ $ji->job_location }}</small></div>
                                            <div class="d-flex justify-content-between mt-3">
                                                <span>RM {{ $ji->job_salary }}</span>

                                                @if($ji->status == 'Occupied')
                                                    @can('is-distributor')
                                                        <button type="button" id="btn_up_job"
                                                                class="btn btn-lg btn-outline-primary">Update
                                                        </button>
                                                    @endcan
                                                    @can('is-user')
                                                        <button type="button" disabled class="btn btn-lg btn-outline-dark">Apply</button>
                                                    @endcan
                                                @elseif ($ji->status == 'Not Occupied')
                                                    <button
                                                        type="button"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#jobModal"
                                                        data-usrname="{{ $ji->name }}"
                                                        data-usremail="{{ $ji->email }}"
                                                        data-jobloc="{{ $ji->job_location }}"
                                                        data-jobsalary="{{ $ji->job_salary }}"
                                                        data-jobname="{{ $ji->job_name }}"
                                                        data-jobid="{{ $ji->job_id }}"
                                                        data-joboccupy="{{ $ji->occupied_by }}"
                                                        data-jobuserid="{{ Auth::id() }}"
                                                        class="btn btn-lg btn-outline-dark"
                                                    >Apply
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Job -->
        <div class="modal fade" id="jobModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                <form>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Job's Application</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row ">
                                <dt class="col-sm-4">Person in Charge</dt>
                                <dd class="col-sm-5">
                                    <p id="pic_job">You can use the mark tag to
                                        <mark>highlight</mark>
                                        text.
                                    </p>
                                    <p id="job_id" hidden>You can use the mark tag to
                                        <mark>highlight</mark>
                                        text.
                                    </p>
                                    <p id="job_user_id" hidden>You can use the mark tag to
                                        <mark>highlight</mark>
                                        text.
                                    </p>
                                </dd>
                                <dt class="col-sm-4">Email</dt>
                                <dd class="col-sm-5">
                                    <p id="job_email">You can use the mark tag to
                                        <mark>highlight</mark>
                                        text.
                                    </p>
                                </dd>
                                <dt class="col-sm-4">Contact No.</dt>
                                <dd class="col-sm-5">
                                    <p>+(60) 12-217 8319</p>
                                </dd>
                                <dt class="col-sm-4">Location</dt>
                                <dd class="col-sm-5">
                                    <p id="job_loc">+(60) 12-217 8319</p>
                                </dd>
                                <dt class="col-sm-4">Rate</dt>
                                <dd class="col-sm-5">
                                    <p id="job_rate">RM 150</p>
                                </dd>
                                <dt class="col-sm-4">Job Scope</dt>
                                <dd class="col-sm-5">
                                    <p id="job_name">PC Builder</p>
                                </dd>
                                <dt class="col-sm-4">Applied By</dt>
                                <dd class="col-sm-5">
                                    <p id="job_occupy_by">PC Builder</p>
                                </dd>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="btn_apply_job" style="width: 100%">Apply
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal for update RMA status -->
        <div class="modal fade" id="staticRMA" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <form>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Update My-RMA</h5>
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
                                                    <select class="form-select" id="floatingSelectStatus"
                                                            aria-label="Floating label select example"
                                                            style="height: 60px">
                                                        <option value="Pending Checking">Pending Checking</option>
                                                        <option value="Received">Received</option>
                                                        <option value="Repair">Repair</option>
                                                        <option value="Replacement 1-1">Replacement 1-1</option>
                                                        <option value="Shipped">Shipped</option>
                                                    </select>
                                                    <label for="floatingSelect">Select Status</label>
                                                </div>
                                            </dd>
                                            <dt class="col-sm-3">Receive At</dt>
                                            <dd class="col-sm-9">
                                                <div class="form-floating">
                                                    <input type="date" class="form-control" id="rma_receive_at">
                                                    <label for="date">Date Of Arrival</label>
                                                </div>
                                            </dd>
                                            <dt class="col-sm-3">Requested RMA At</dt>
                                            <dd class="col-sm-9">
                                                <h5 id="rma_request_at"></h5>
                                            </dd>
                                            <dt class="col-sm-3">Tracking No.</dt>
                                            <dd class="col-sm-9">
                                                <input id="rma_tracking_no" class="form-control">
                                            </dd>
                                            <dt class="col-sm-3">Remark</dt>
                                            <dd class="col-sm-9">
                                                <textarea class="form-control" id="rma_resolution" rows="3"></textarea>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" style="width: 89%" id="btn_update_rma">
                                Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @can('is-user')
            <div class="row">
                <div class="col-xl-12 col-lg-11">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0" style="font-weight: bold">My RMA Requests</h6>
                        </div>
                        <div class="card-body border-3 border-bottom border-warning">
                            @if($rmaInfo->count() == 0)
                                <div class="row text-center">
                                    <h1 class="display-6">Your RMA request will be shown here.</h1>
                                </div>
                            @else
                            <!-- START HERE -->
                                @foreach($rmaInfo as $rma)
                                    <div class="row">
                                        <div class="col-12 col-md-4">
                                            <div class="p-3 d-flex align-items-center">
                                                <div class="mr-3">
                                                    <img src="/storage/product/{{ $rma->product_image_path }}"
                                                         width="100" height="100"/>
                                                </div>

                                                <div class="mx-3">
                                                    <h5>{{ $rma->product_name }}</h5>
                                                    <div class="text-muted monospace">
                                                        {{ $rma->product_sn }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-2 d-flex align-items-center justify-content-center">
                                            <div class="text-center">
                                                <div>
                                                    Serial Number
                                                </div>
                                                <div class="monospace text-primary">
                                                    {{ $rma->sn_no }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-2 d-flex align-items-center justify-content-center">
                                            <div class="text-center">
                                                <div>
                                                    Status
                                                </div>
                                                <div class="text-primary">
                                                    {{ $rma->status }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-2 d-flex align-items-center justify-content-center">
                                            <div class="text-center">
                                                <div>
                                                    Tracking No.
                                                </div>
                                                <div class="text-primary">
                                                    @if($rma->tracking_no != null)
                                                        <a class="btn btn-outline-dark" style="font-size: 15px"
                                                           onclick="linkTrack(this.innerText)">{{ $rma->tracking_no }}</a>
                                                    @else
                                                        Not Available
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-2 d-flex align-items-center justify-content-center">
                                            <div class="p-3 text-center">
                                                <div class="text-primary monospace">
                                                    <a target="_blank" href="{{ route('rma.job-sheet', $rma->id) }}"
                                                       class="btn btn-sm btn-primary">RMA Request Form</a>
                                                    <a href="/storage/rma/{{ $rma->file_path }}" target="_blank">
                                                        <button class="btn"><i class="fa fa-download"></i> Download File
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            <!-- END HERE -->
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        @can('is-reseller-distributor')
            <div class="row">
                <div class="col-xl-12 col-lg-11">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0" style="font-weight: bold">All RMA Requests</h6>
                        </div>
                        <div class="card-body border-3 border-bottom border-warning">
                            @if($rmaInfoDistri->count() == 0)
                                <div class="row text-center">
                                    <h1 class="display-6">RMA request from customer will be shown here.</h1>
                                </div>
                            @else
                            <!-- START HERE -->
                                @foreach($rmaInfoDistri as $rma)
                                    <div class="row">
                                        <div class="col-12 col-md-4">
                                            <div class="p-3 d-flex align-items-center">
                                                <div class="mr-3">
                                                    <img src="/storage/product/{{ $rma->product_image_path }}"
                                                         width="100" height="100"/>
                                                </div>

                                                <div class="mx-3">
                                                    <h5>{{ $rma->product_name }}</h5>
                                                    <div class="text-muted monospace">
                                                        {{ $rma->product_sn }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-2 d-flex align-items-center justify-content-center">
                                            <div class="text-center">
                                                <div>
                                                    Serial Number
                                                </div>
                                                <div class="monospace text-primary">
                                                    {{ $rma->sn_no }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-2 d-flex align-items-center justify-content-center">
                                            <div class="text-center">
                                                <div>
                                                    Status
                                                </div>
                                                <div class="text-primary">
                                                    {{ $rma->status }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-2 d-flex align-items-center justify-content-center">
                                            <div class="text-center">
                                                <div>
                                                    Tracking No.
                                                </div>
                                                <div class="text-primary">
                                                    @if($rma->tracking_no != null)
                                                        <a class="btn btn-outline-dark" style="font-size: 15px"
                                                           onclick="linkTrack(this.innerText)">{{ $rma->tracking_no }}</a>
                                                    @else
                                                        Not Available
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-2 d-flex align-items-center justify-content-center">
                                            <div class="p-3 text-center">
                                                <div class="text-primary monospace">
                                                    <a target="_blank" href="{{ route('rma.job-sheet', $rma->id) }}"
                                                       class="btn btn-sm btn-primary">RMA Request Form</a>
                                                    <a href="/storage/rma/{{ $rma->file_path }}" target="_blank">
                                                        <button class="btn"><i class="fa fa-download"></i> Download File
                                                        </button>
                                                    </a>
                                                    <a
                                                        href="#"
                                                        data-myrmaid="{{ $rma->id }}"
                                                        data-myprodpic="{{ $rma->product_image_path }}"
                                                        data-myrmastatus="{{ $rma->status }}"
                                                        data-myrmareason="{{ $rma->reason }}"
                                                        data-myrmareqat="{{ date('d-M-Y H:i A', strtotime($rma->created_at)) }}"
                                                        data-mytrack="{{ $rma->tracking_no }}"
                                                        data-myresolution="{{ $rma->resolve_solution }}"
                                                        data-myreceive="{{ $rma->receive_at }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#staticRMA">
                                                        @can('is-distributor')
                                                            <button class="btn btn-sm btn-warning"><i
                                                                    data-feather="alert-triangle" class="feather-16"
                                                                    style="margin-bottom: 5px"></i> Update RMA Status
                                                            </button>
                                                        @endcan
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            <!-- END HERE -->
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    @endcan

    @guest
        <div class="row mt-3">
            <div class="col-xl-12 col-lg-11">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0" style="font-weight: bold">My Map</h6>
                    </div>
                    <div class="card-body border-3 border-bottom border-warning">
                        <div class="row">
                            <div class="col-8">
                                <div id="map" class="map"></div>
                            </div>
                            <div class="col">
                                <h1 class="display-6">Xmiryna Technology [SA0546866-M]</h1>
                                No. 79 Jalan Taman Melati 1,<br>
                                Taman Melati, Setapak,<br>
                                53100, Kuala Lumpur<br>
                                xmiryna.tech@outlook.com <br>
                                <b>+(60) 17-217 8319</b> / <strong>xmiryna.com.my</strong>
                                <div class="col mt-lg-5 text-center">
                                    <i data-feather="message-circle"></i>
                                    <i data-feather="truck" class="ms-5"></i>
                                    <i data-feather="phone" class="ms-5"></i>
                                    <i data-feather="at-sign" class="ms-5"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endguest

    <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/build/ol.js"></script>
    <script src="//www.tracking.my/track-button.js"></script>
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
                    data: [2, 2, 3, 2, 1]
                }, {
                    name: 'Joe',
                    data: [3, 4, 4, -2, 5]
                }]
            });

            Highcharts.chart('graph2', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'Browser market shares in January, 2021'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: false
                        },
                        showInLegend: true
                    }
                },
                series: [{
                    name: 'Brands',
                    colorByPoint: true,
                    data: [{
                        name: 'Chrome',
                        y: 61.41,
                        sliced: true,
                        selected: true
                    }, {
                        name: 'Internet Explorer',
                        y: 11.84
                    }, {
                        name: 'Firefox',
                        y: 10.85
                    }, {
                        name: 'Edge',
                        y: 4.67
                    }, {
                        name: 'Safari',
                        y: 4.18
                    }, {
                        name: 'Other',
                        y: 7.05
                    }]
                }],
                credits: {
                    enabled: false
                }
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#btn_update_rma").click(function (e) {

            e.preventDefault();

            var status = $("#floatingSelectStatus").val();
            var receive_at = $("#rma_receive_at").val();
            var remark = $("#rma_resolution").val();
            var tracking = $("#rma_tracking_no").val();
            var rma_id = $("#rma_no").val();

            $.ajax({
                type: 'PATCH',
                url: "http://127.0.0.1:8000/rma/rma-update/" + rma_id,
                data: {
                    rma_status: status,
                    receive: receive_at,
                    remark_note: remark,
                    track_no: tracking
                },
                success: function (data) {
                    if (data['success'])
                        location.reload();
                    else
                        alert('EXISTING.')

                }
            });
        });

        $("#btn_apply_job").click(function (e) {

            e.preventDefault();

            var job_id = $("#job_id").val();
            var job_u_id = $("#job_user_id").val();

            $.ajax({
                type: 'PATCH',
                url: "http://127.0.0.1:8000/job/update/" + job_id,
                data: {
                    usr_id: job_u_id,
                },
                success: function (data) {
                    if (data['success'])
                        location.reload();
                    else
                        alert('EXISTING.')

                }
            });
        });

        $('#staticRMA').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var rma_id = button.data('myrmaid') // Extract info from data-* attributes
            var prod_pic = button.data('myprodpic') // Extract info from data-* attributes
            var rma_status = button.data('myrmastatus') // Extract info from data-* attributes
            var rma_reason = button.data('myrmareason') // Extract info from data-* attributes
            var rma_request_at = button.data('myrmareqat') // Extract info from data-* attributes
            var rma_tracking_no = button.data('mytrack') // Extract info from data-* attributes
            var rma_resolution = button.data('myresolution') // Extract info from data-* attributes
            var rma_receive = button.data('myreceive') // Extract info from data-* attributes

            var modal = $(this)
            modal.find('.modal-body #rma_no').val(rma_id);
            modal.find('.modal-body #rma_status').val(rma_status);
            modal.find('.modal-body #rma_reason').val(rma_reason);
            modal.find('.modal-body #rma_request_at').val(rma_request_at);
            modal.find('.modal-body #rma_tracking_no').val(rma_tracking_no);
            modal.find('.modal-body #rma_resolution').val(rma_resolution);
            modal.find('.modal-body #rma_receive_at').val(rma_receive);

            modal.find('.modal-body #floatingSelectStatus').val(rma_status);

            document.getElementById("rma_no").innerText = "#" + rma_id;
            document.getElementById("rma_status").innerText = rma_status;
            document.getElementById("rma_reason").innerText = rma_reason;
            document.getElementById("rma_request_at").innerText = rma_request_at;
            document.getElementById("rma_tracking_no").innerText = rma_tracking_no;
            document.getElementById("rma_resolution").innerText = rma_resolution;
            document.getElementById("rma_receive_at").value = rma_receive;
            document.getElementById("floatingSelectStatus").value = rma_status;
            document.getElementById("myProdImg").src = "/storage/product/" + prod_pic;
        });

        $('#jobModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var job_person_in_charge = button.data('usrname')
            var job_email = button.data('usremail')
            var job_location = button.data('jobloc')
            var job_rate = button.data('jobsalary')
            var job_scope = button.data('jobname')
            var job_id = button.data('jobid')
            var job_occ_by = button.data('joboccupy')
            var job_usr_id = button.data('jobuserid')

            var modal = $(this)
            modal.find('.modal-body #pic_job').val(job_person_in_charge);
            modal.find('.modal-body #job_email').val(job_email);
            modal.find('.modal-body #job_loc').val(job_location);
            modal.find('.modal-body #job_rate').val(job_rate);
            modal.find('.modal-body #job_name').val(job_scope);
            modal.find('.modal-body #job_id').val(job_id);
            modal.find('.modal-body #job_occupy_by').val(job_occ_by);
            modal.find('.modal-body #job_user_id').val(job_usr_id);

            document.getElementById("pic_job").innerText = job_person_in_charge;
            document.getElementById("job_email").innerText = job_email;
            document.getElementById("job_loc").innerText = job_location;
            document.getElementById("job_rate").innerText = 'RM ' + job_rate;
            document.getElementById("job_name").innerText = job_scope;
            document.getElementById("job_id").innerText = job_id;
            document.getElementById("job_occupy_by").innerText = job_occ_by;
            document.getElementById("job_user_id").innerText = job_usr_id;
        });

        function linkTrack(num) {
            TrackButton.track({
                tracking_no: num
            });
        }

        var map = new ol.Map({
            target: 'map',
            controls: ol.control.defaults({attribution: false}),
            layers: [
                new ol.layer.Tile({
                    source: new ol.source.OSM()
                })
            ],
            view: new ol.View({
                center: ol.proj.fromLonLat([101.72427413995997, 3.221067247294041]),
                zoom: 17
            })
        });
        var layer = new ol.layer.Vector({
            source: new ol.source.Vector({
                features: [
                    new ol.Feature({
                        geometry: new ol.geom.Point(ol.proj.fromLonLat([101.72427413995997, 3.221067247294041]))
                    })
                ]
            })
        });
        map.addLayer(layer);
    </script>
@endsection
