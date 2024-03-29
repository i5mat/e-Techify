@extends('templates.main')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/css/ol.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <style>
        .btn-outline-dark {
            border-radius: 35px;
            font-size: 12px;
            box-shadow: none
        }

        .btn-outline-primary {
            border-radius: 35px;
            font-size: 12px;
            box-shadow: none
        }

        .col-md-3 {
            margin-top: 5px
        }

        .map {
            height: 400px;
            width: 100%;
        }

        .highcharts-figure, .highcharts-data-table table {
            min-width: 310px;
            max-width: 800px;
            margin: 1em auto;
        }

        #container {
            height: 442px;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #EBEBEB;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }
        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }
        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }
        .highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
            padding: 0.5em;
        }
        .highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }
        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }


    </style>

    @can('logged-in')
        <h1 class="display-3">Hi, {{ Auth::user()->name }} !</h1>

        @can('is-user')
            <div class="row mb-3">
                <div class="col">
                    <div class="card">
                        <div class="card-body border-3 border-start border-success shadow h-100">
                            <div class="row g-0 align-items-center">
                                <div class="col" style="margin-right: 2px">
                                    <div class="text-sm text-success text-uppercase mb-1" style="font-weight: bold">
                                        Total Spending
                                    </div>
                                    <div class="h5 mb-0" style="font-weight: bold" id="total_cust_spend">{{ $getCustTotalSpend }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-dollar-sign fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div class="card-body border-3 border-start border-danger shadow h-100">
                            <div class="row g-0 align-items-center">
                                <div class="col" style="margin-right: 2px">
                                    <div class="text-sm text-danger text-uppercase mb-1" style="font-weight: bold">
                                        Total Orders Created
                                    </div>
                                    <div class="h5 mb-0" style="font-weight: bold">{{ $getCustTotalOrder }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-archive fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        @can('is-distributor')
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body border-3 border-start border-warning shadow h-100">
                            <div class="row g-0 align-items-center">
                                <div class="col" style="margin-right: 2px">
                                    <div class="text-sm text-warning text-uppercase mb-1" style="font-weight: bold">
                                        Total RMA
                                    </div>
                                    <div class="h5 mb-0" style="font-weight: bold">{{ $getRMA }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-wrench fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div class="card-body border-3 border-start border-danger shadow h-100">
                            <div class="row g-0 align-items-center">
                                <div class="col" style="margin-right: 2px">
                                    <div class="text-sm text-danger text-uppercase mb-1" style="font-weight: bold">
                                        Total Products Sold
                                    </div>
                                    <div class="h5 mb-0" style="font-weight: bold">{{ $getTotalSold }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-cubes fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0">Earnings Overview</h6>
                        </div>
                        <div class="card-body border-3 border-bottom border-warning">
                            @if($getDataDistributor->count() <= 0)
                                <div class="row text-center">
                                    <img src="/image/no-earnings.png" style="width: 500px; height: 400px; display: block; margin-left: auto; margin-right: auto">
                                    <h1 class="display-6">No earnings recorded.</h1>
                                </div>
                            @else
                                <div id="graph"></div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0">Revenue Sources</h6>
                        </div>
                        <div class="card-body pt-4 pb-2 border-3 border-bottom border-warning">
                            @if($getPieChartData->count() > 0)
                                <div id="graph2"></div>
                            @else
                                <div class="row text-center">
                                    <img src="/image/no-revenue.png" style="width: 400px; height: 400px; display: block; margin-left: auto; margin-right: auto">
                                    <h1 class="display-6">No revenue recorded.</h1>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-xl-12 col-lg-11">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0">Customer RMA Requests</h6>
                        </div>
                        <div class="card-body border-3 border-bottom border-warning">
                            @if($rmaInfoDistri->count() == 0)
                                <div class="row text-center">
                                    <img src="/image/no-rma.png" style="width: 400px; height: 400px; display: block; margin-left: auto; margin-right: auto">
                                    <h1 class="display-6">RMA request from customer will be shown here.</h1>
                                </div>
                            @else
                            <!-- START HERE -->
                                <div id="table_data">
                                    @include('pagination')
                                </div>
                                <!-- END HERE -->
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        @can('is-reseller')
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body border-3 border-start border-primary shadow h-100">
                            <div class="row g-0 align-items-center">
                                <div class="col" style="margin-right: 2px">
                                    <div class="text-sm text-primary text-uppercase mb-1" style="font-weight: bold">
                                        Earnings Monthly
                                    </div>
                                    <div class="h5 mb-0" style="font-weight: bold" id="monthly_earn">{{ $getConfirmOrderMonthly->sale_per_month }}</div>
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
                                    <div class="h5 mb-0" style="font-weight: bold" id="annual_earn">{{ $getConfirmOrder }}</div>
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
                                        RMA Requests
                                    </div>
                                    <div class="h5 mb-0" style="font-weight: bold">{{ $getRMATotal }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-wrench fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-xl-12 col-lg-11">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0">Customer RMA Requests</h6>

                        </div>
                        <div class="card-body border-3 border-bottom border-warning">
                            @if($rmaInfoReseller->count() == 0)
                                <div class="row text-center">
                                    <h1 class="display-6">RMA request from customer will be shown here.</h1>
                                </div>
                            @else
                            <!-- START HERE -->
                                <div id="table_data">
                                    @include('pagination')
                                </div>
                            <!-- END HERE -->
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 10px">
                <div class="col-xl-7 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0">Brand Cumulative</h6>
                        </div>
                        <div class="card-body border-3 border-bottom border-warning">
                            <div id="container"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0">Earnings Monthly</h6>
                        </div>
                        <div class="card-body border-3 border-bottom border-warning">
                            <div id="earnings"></div>

                            <button class="btn btn-primary mt-1" id="plain">Plain</button>
                            <button class="btn btn-primary mt-1" id="inverted">Inverted</button>
                            <button class="btn btn-primary mt-1" id="polar">Polar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 10px">
                <div class="col">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0">Quarter Earnings</h6>
                        </div>
                        <div class="card-body border-3 border-bottom border-warning">
                            <div id="quarter_chart"></div>

                            <button class="btn btn-primary mt-1" id="all_quarter">All</button>
                            <button class="btn btn-primary mt-1" id="q_1">Q1</button>
                            <button class="btn btn-primary mt-1" id="q_2">Q2</button>
                            <button class="btn btn-primary mt-1" id="q_3">Q3</button>
                            <button class="btn btn-primary mt-1" id="q_4">Q4</button>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        @can('is-user')
            <div class="row">
                <div class="col-xl-12 col-lg-11">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0">My RMA Requests</h6>
                        </div>
                        <div class="card-body border-3 border-bottom border-warning">
                            @if($rmaInfo->count() == 0)
                                <div class="row text-center">
                                    <img src="/image/no-rma.png" style="width: 400px; height: 400px; display: block; margin-left: auto; margin-right: auto">
                                    <h1 class="display-6">Your RMA request will be shown here.</h1>
                                </div>
                            @else
                            <!-- START HERE -->
                                <div id="table_data">
                                    @include('pagination')
                                </div>
                            <!-- END HERE -->
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        <div class="row">
            <div class="col-xl-12 col-lg-11">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0">Job Offers</h6>
                    </div>
                    <div class="card-body border-3 border-bottom border-warning">
                        @if($jobInfo->count() <= 0)
                            <div class="row text-center">
                                <img src="/image/no-job.png" style="width: 400px; height: 400px; display: block; margin-left: auto; margin-right: auto">
                                <h1 class="display-6">All job inserted by you will be displayed here.</h1>
                            </div>
                        @else
                            <div class="owl-carousel owl-theme">
                                @foreach($jobInfo as $ji)
                                    <div class="item" style="width:307px">
                                        <div class="col">
                                            <div class="card p-2 mb-3 ms-1" style="border: none">
                                                <div class="text-right badge bg-warning"><small
                                                        class="lead">{{ $ji->job_type }}</small></div>
                                                <div class="text-center mt-2 p-3"> <i class="fa fa-child fa-4x"></i> <span
                                                        class="d-block font-weight-bold">{{ $ji->job_name }}</span>
                                                    <span @if($ji->status == 'Not Occupied') class="badge bg-primary mt-2"
                                                          @else class="badge bg-success mt-2"
                                                          @endif style="color: white">{{ $ji->status }}</span>
                                                    <hr>
                                                    <span>{{ $ji->name }}</span>
                                                    <div class="d-flex flex-row align-items-center justify-content-center"><i
                                                            class="fa fa-location-arrow"></i> <small
                                                            class="mx-1"> {{ $ji->job_location }}</small></div>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <span>RM <b>{{ $ji->job_salary }}</b> /hour</span>

                                                        @if($ji->status == 'Occupied')
                                                            @can('is-reseller-distributor')
                                                                <button
                                                                    type="button"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#updateJobStatusModal"
                                                                    data-jobname="{{ $ji->job_name }}"
                                                                    data-jobid="{{ $ji->job_id }}"
                                                                    data-jobstats="{{ $ji->status }}"
                                                                    class="btn btn-lg btn-outline-primary"
                                                                >Update Status
                                                                </button>
                                                            @endcan
                                                            @can('is-user')
                                                                <button type="button" disabled class="btn btn-lg btn-outline-dark">Apply</button>
                                                            @endcan
                                                        @elseif ($ji->status == 'Not Occupied')
                                                            @can('is-user')
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
                                                                    data-jobuserid="{{ Auth::id() }}"
                                                                    class="btn btn-lg btn-outline-dark"
                                                                >Apply
                                                                </button>
                                                            @else
                                                                <button
                                                                    type="button"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#updateJobModal"
                                                                    data-usrname="{{ $ji->name }}"
                                                                    data-usremail="{{ $ji->email }}"
                                                                    data-jobloc="{{ $ji->job_location }}"
                                                                    data-jobsalary="{{ $ji->job_salary }}"
                                                                    data-jobname="{{ $ji->job_name }}"
                                                                    data-jobid="{{ $ji->job_id }}"
                                                                    data-joboccupy="{{ $ji->occupied_by }}"
                                                                    data-jobuserid="{{ Auth::id() }}"
                                                                    data-jobtype="{{ $ji->job_type }}"
                                                                    class="btn btn-lg btn-outline-dark"
                                                                >View
                                                                </button>
                                                            @endcan
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for update Job status -->
        <div class="modal fade" id="updateJobStatusModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <form>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Job's Application</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row ">
                                <dt class="col-sm-4">Job Scope</dt>
                                <dd class="col-sm-5">
                                    <h5 id="job_name_status_modal">PC Builder</h5>
                                    <p id="job_id_status_modal" hidden>You can use the mark tag to
                                        <mark>highlight</mark>
                                        text.
                                    </p>
                                </dd>
                                <dt class="col-sm-4">Update Status</dt>
                                <dd class="col-sm-8">
                                    <div class="form-floating">
                                        <select class="form-select" id="floatingUpdateStatus"
                                                aria-label="Floating label select example"
                                                style="height: 60px">
                                            <option value="Occupied">Occupied</option>
                                            <option value="Not Occupied">Not Occupied</option>
                                        </select>
                                        <label for="floatingSelect">Select Status</label>
                                    </div>
                                </dd>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="btn_update_job_status" style="width: 100%">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal for update Job -->
        <div class="modal fade" id="updateJobModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
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
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="job_loc">
                                        <label for="text">Job Location</label>
                                    </div>
                                </dd>
                                <dt class="col-sm-4">Rate (RM)</dt>
                                <dd class="col-sm-5">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="job_rate">
                                        <label for="number">Job Rate</label>
                                    </div>
                                </dd>
                                <dt class="col-sm-4">Job Scope</dt>
                                <dd class="col-sm-5">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="job_names">
                                        <label for="text">Job Scope</label>
                                    </div>
                                </dd>
                                <dt class="col-sm-4">Job Type</dt>
                                <dd class="col-sm-5">
                                    <div class="form-floating">
                                        <select class="form-select" name="job_type_update" id="job_type_update">
                                            <option selected>Please select...</option>
                                            <option value="Full Time">Full Time</option>
                                            <option value="Part Time">Part Time</option>
                                            <option value="Temporary">Temporary</option>
                                            <option value="Permanent">Permanent</option>
                                        </select>
                                        <label for="job_type">Job Type</label>
                                    </div>
                                </dd>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="btn_update_job" style="width: 100%">Apply</button>
                        </div>
                    </div>
                </form>
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
                                    <p id="pic_job_apply">You can use the mark tag to
                                        <mark>highlight</mark>
                                        text.
                                    </p>
                                    <p id="job_id_apply" hidden>You can use the mark tag to
                                        <mark>highlight</mark>
                                        text.
                                    </p>
                                    <p id="job_user_id_apply" hidden>You can use the mark tag to
                                        <mark>highlight</mark>
                                        text.
                                    </p>
                                </dd>
                                <dt class="col-sm-4">Email</dt>
                                <dd class="col-sm-5">
                                    <p id="job_email_apply">You can use the mark tag to
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
                                    <p id="job_loc_apply">+(60) 12-217 8319</p>
                                </dd>
                                <dt class="col-sm-4">Rate</dt>
                                <dd class="col-sm-5">
                                    <p id="job_rate_apply">RM 150</p>
                                </dd>
                                <dt class="col-sm-4">Job Scope</dt>
                                <dd class="col-sm-5">
                                    <p id="job_name_apply">PC Builder</p>
                                </dd>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="btn_apply_job" style="width: 100%">Apply</button>
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
                        <div class="steps d-flex flex-wrap flex-sm-nowrap justify-content-between padding-top-2x padding-bottom-1x mt-3">
                            <div class="step" id="pending_receive_step">
                                <div class="step-icon-wrap">
                                    <div class="step-icon"><i data-feather="clipboard" class="feather-32" style="margin-bottom: 15px"></i></div>
                                </div>
                                <h4 class="step-title">Pending Receive</h4>
                            </div>
                            <div class="step" id="received_step">
                                <div class="step-icon-wrap">
                                    <div class="step-icon"><i data-feather="arrow-down-circle" class="feather-32" style="margin-bottom: 15px"></i></div>
                                </div>
                                <h4 class="step-title">Received</h4>
                            </div>
                            <div class="step" id="pending_checking_step">
                                <div class="step-icon-wrap">
                                    <div class="step-icon"><i data-feather="check-circle" class="feather-32" style="margin-bottom: 15px"></i></div>
                                </div>
                                <h4 class="step-title">Pending Checking</h4>
                            </div>
                            <div class="step" id="repair_step">
                                <div class="step-icon-wrap">
                                    <div class="step-icon"><i class="fa fa-wrench" style="margin-bottom: 20px"></i></div>
                                </div>
                                <h4 class="step-title">Repair / Replacement 1-1</h4>
                            </div>
                            <div class="step" id="shipped_step">
                                <div class="step-icon-wrap">
                                    <div class="step-icon"><i data-feather="truck" class="feather-32" style="margin-bottom: 15px"></i></div>
                                </div>
                                <h4 class="step-title">Shipped</h4>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <div class="row text-center">
                                    <div class="col-xl">
                                        <img id="myProdImg" src="" width="250" height="250">
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
                                            @can('is-reseller-distributor')
                                            <dt class="col-sm-3">Update Status</dt>
                                            <dd class="col-sm-9">
                                                <div class="form-floating">
                                                    <select class="form-select" id="floatingSelectStatus"
                                                            aria-label="Floating label select example"
                                                            style="height: 60px">
                                                        <option value="Pending Receive">Pending Receive</option>
                                                        <option value="Received">Received</option>
                                                        <option value="Pending Checking">Pending Checking</option>
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
                                            @endcan
                                            <dt class="col-sm-3">Requested RMA At</dt>
                                            <dd class="col-sm-9">
                                                <h6 id="rma_request_at"></h6>
                                            </dd>
                                            <dt class="col-sm-3">Tracking No.</dt>
                                            @can('is-user')
                                                <dd class="col-sm-9">
                                                    <h6 id="rma_tracking_no"></h6>
                                                </dd>
                                            @else
                                                <dd class="col-sm-9">
                                                    <input id="rma_tracking_no" class="form-control">
                                                </dd>
                                            @endcan
                                            <dt class="col-sm-3">Remark</dt>
                                            @can('is-user')
                                                <dd class="col-sm-9">
                                                    <h6 id="rma_resolution"></h6>
                                                </dd>
                                                <dt class="col-sm-3">Address</dt>
                                                <dd class="col-sm-9">
                                                    <h6 id="rma_usr_address">LOL</h6>
                                                </dd>
                                            @else
                                                <dd class="col-sm-9">
                                                    <textarea class="form-control" id="rma_resolution" rows="3"></textarea>
                                                </dd>
                                            @endcan
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @can('is-reseller-distributor')
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" style="width: 89%" id="btn_update_rma">
                                Update
                            </button>
                        </div>
                        @endcan
                    </div>
                </form>
            </div>
        </div>
    @endcan

    @guest

        <div class="container py-4">
            <div class="p-5 mb-4 bg-light rounded-3" style="background-image: url('image/header-xt.jpg')">
                <div class="container-fluid py-5">
                    <h1 class="display-5 fw-bold">Welcome to Xmiryna Technology!</h1>
                    <p class="col-md-8 fs-4">You can play if you can picture it. We offer the most advanced processes, from quality assurance through delivery to your door.</p>
                </div>
            </div>

            <div class="col-md-12 mt-3 mb-3">
                <div class="owl-carousel owl-theme" id="rig_carousel">
                    <div class="item">
                        <img src="/image/XT-logo.png">
                    </div>
                    <div class="item">
                        <img src="/image/XT-logo.png">
                    </div>
                    <div class="item">
                        <img src="/image/XT-logo.png">
                    </div>
                    <div class="item">
                        <img src="/image/XT-logo.png">
                    </div>
                    <div class="item">
                        <img src="/image/XT-logo.png">
                    </div>
                    <div class="item">
                        <img src="/image/XT-logo.png">
                    </div>
                </div>
            </div>

            <div class="row align-items-md-stretch">
                <div class="col-md-12">
                    <h1 class="display-6">Why Choose Us?</h1>
                </div>
                <div class="col-md-4">
                    <div class="h-100 p-5 text-white bg-dark rounded-3">
                        <h2>Latest Build</h2>
                        <p>To build your perfect rig, we use the most up-to-date gear and technologies. You may rest confident that it will be installed by one of our skilled professionals.</p>
                        <i class="fa fa-quidditch fa-2x"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="h-100 p-5 text-white bg-dark rounded-3">
                        <h2>Light Installment Plan</h2>
                        <p>You can pay over time - up to 3 months - so you can have your ideal gear without breaking the bank.</p>
                        <i class="fa fa-credit-card fa-2x"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="h-100 p-5 text-white bg-dark rounded-3">
                        <h2>Gentle Handling</h2>
                        <p>All rigs built by us will be handled with care from start to finish, all the way to the courier hands.</p>
                        <i class="fa fa-truck fa-2x"></i>
                    </div>
                </div>
                <div class="col-md-8 mt-3">
                    <div class="h-100 p-5 text-white bg-dark rounded-3">
                        <h2>Local Distributor Warranty</h2>
                        <p>All of our builds come with a three-year warranty, which includes every single item we install on your rig. It's less of a hassle, and you won't have to worry about anything.</p>
                        <i class="fa fa-city fa-2x"></i>
                    </div>
                </div>
                <div class="col-md-4 mt-3">
                    <div class="h-100 p-5 text-white bg-dark rounded-3">
                        <h2>Excellent After-Sale Service</h2>
                        <p>We won't ask too much questions, just them in!</p>
                        <i class="fa fa-wrench fa-2x"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mt-5">
                <h1 class="display-6">Our Location</h1>
            </div>
            <div class="row mt-3">
                <div class="col-xl-12 col-lg-11">
                    <div class="card shadow">
                        <div class="card-body">
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
        </div>
    @endguest

    <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/build/ol.js"></script>
    <script src="//www.tracking.my/track-button.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="application/javascript">
        feather.replace();

        @guest
            $('#rig_carousel').owlCarousel({
                loop:true,
                margin:10,
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:3
                    },
                    1000:{
                        items:5
                    }
                }
            })
        @endguest

        tippy('#tooltip_rma', {
            content: 'RMA Info',
        });

        @can('is-distributor')
            tippy('#tooltip_invoice', {
                content: 'Customer Invoice',
            });
        @else
        tippy('#tooltip_invoice', {
            content: 'Invoice',
        });
        @endcan

        tippy('#tooltip_jobsheet', {
            content: 'RMA Job sheet',
        });

        $('.owl-carousel').owlCarousel({
            margin:10,
            loop:true,
            autoWidth:true,
            items:4,
        });

        var formatter = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'MYR',
        });

        $(function(){
            var dtToday = new Date();

            var month = dtToday.getMonth() + 1;
            var day = dtToday.getDate();
            var year = dtToday.getFullYear();
            if(month < 10)
                month = '0' + month.toString();
            if(day < 10)
                day = '0' + day.toString();

            var maxDate = year + '-' + month + '-' + day;
            $('#rma_receive_at').attr('max', maxDate);
        });

        @can('is-user')
            var test3 = $("#total_cust_spend").text();
            document.getElementById('total_cust_spend').innerHTML = formatter.format(test3);

            $(document).ready(function() {

                $(document).on('click', '.pagination a', function(event) {
                    event.preventDefault();
                    var page = $(this).attr('href').split('page=')[1];
                    fetch_data(page);
                });

                function fetch_data(page) {
                    var l = window.location;

                    // the request path should be
                    // domain.com/welcome/pagination
                    console.log(l.origin+ ' ' +l.pathname)

                    $.ajax({
                        url: l.origin + l.pathname + "/pagination?page=" + page,
                        success: function(rmaInfo) {
                            $('#table_data').html(rmaInfo);
                        }
                    });
                }
            });
        @endcan

        @can('is-reseller')
            var test = $("#annual_earn").text();
            var test2 = $("#monthly_earn").text();

            document.getElementById('annual_earn').innerHTML = formatter.format(test);
            document.getElementById('monthly_earn').innerHTML = formatter.format(test2);

            $(document).ready(function() {

                $(document).on('click', '.pagination a', function(event) {
                    event.preventDefault();
                    var page = $(this).attr('href').split('page=')[1];
                    fetch_data(page);
                });

                function fetch_data(page) {
                    var l = window.location;

                    // the request path should be
                    // domain.com/welcome/pagination
                    console.log(l.origin+ ' ' +l.pathname)

                    $.ajax({
                        url: l.origin + l.pathname + "/pagination?page=" + page,
                        success: function(rmaInfoReseller) {
                            $('#table_data').html(rmaInfoReseller);
                        }
                    });
                }
            });
        @endcan

        @can('is-distributor')
        document.addEventListener('DOMContentLoaded', function () {

            var myC = @json($getDataDistributor);
            var myD = @json($getPieChartData);

            //console.log(myC);
            console.log(myD);

            @if($getDataDistributor->count() > 0)
            Highcharts.chart('graph', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Brand Counter Sale Per Month'
                },
                subtitle: {
                    text: 'Total orders sort by brand per month for XT Technology'
                },
                credits: false,
                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Total'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: (function() {
                    var series = [];

                    $.each(myC, function(i, item) {
                        const str = item.total_per_month.split(', ').map(Number);
                        series.push({
                            name: item.product_brand,
                            data: str,
                        });
                        console.log(str)
                    })

                    return series;
                }()),
            });
            @endif

            @if($getPieChartData->count() > 0)
            var data = [];
            for(i in myD){
                const str_num = myD[i].tot_qty.split(' ').map(Number);
                data.push({"name":myD[i].product_brand,"y":parseInt(str_num)});
                console.log(parseInt(str_num))
            }

            Highcharts.chart('graph2', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'Percentage Sold for Each Brand, 2021'
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
                    data: data
                }],
                credits: {
                    enabled: false
                }
            });
            @endif
        });

        $(document).ready(function() {

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetch_data(page);
            });

            function fetch_data(page) {
                var l = window.location;

                // the request path should be
                // domain.com/welcome/pagination
                console.log(l.origin+ ' ' +l.pathname)

                $.ajax({
                    url: l.origin + l.pathname + "/pagination?page=" + page,
                    success: function(rmaInfoDistri) {
                        $('#table_data').html(rmaInfoDistri);
                    }
                });
            }
        });
        @endcan

        @can('is-reseller')
        document.addEventListener('DOMContentLoaded', function () {
            var myA = @json($myArray);
            var myB = @json($findMonth);
            var myZ = @json($getTotalSalesReseller);
            var myQ1 = @json($getQ1);
            var myQ2 = @json($getQ2);
            var myQ3 = @json($getQ3);
            var myQ4 = @json($getQ4);

            console.log(myQ1);

            Highcharts.chart('container', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Brand Counter Sale Per Month'
                },
                subtitle: {
                    text: 'Total orders sort by brand per month for XT Technology'
                },
                credits: false,
                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Total'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: (function() {
                    var series = [];

                    $.each(myA, function(i, item) {
                        const str = item.total_per_month.split(', ').map(Number);
                        series.push({
                            name: item.product_brand,
                            data: str,
                        });
                        console.log(str)
                    })

                    return series;
                }()),
            });

            let str_reseller = [];
            $.each(myZ, function(i, item) {
                str_reseller = item.total_per_month_reseller.split(', ').map(Number);
            })
            console.log(str_reseller)

            const chart = Highcharts.chart('earnings', {
                title: {
                    text: 'Monthly Earnings'
                },
                credits: false,
                subtitle: {
                    text: 'Monthly sales for XT Technology'
                },
                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                },
                chart: {
                    polar: true
                },
                series: [{
                    type: 'column',
                    colorByPoint: true,
                    data: str_reseller,
                    showInLegend: false
                }]
            });

            document.getElementById('plain').addEventListener('click', () => {
                chart.update({
                    chart: {
                        inverted: false,
                        polar: false
                    },
                    subtitle: {
                        text: 'Monthly sales for XT Technology'
                    }
                });
            });

            document.getElementById('inverted').addEventListener('click', () => {
                chart.update({
                    chart: {
                        inverted: true,
                        polar: false
                    },
                    subtitle: {
                        text: 'Monthly sales for XT Technology'
                    }
                });
            });

            document.getElementById('polar').addEventListener('click', () => {
                chart.update({
                    chart: {
                        inverted: false,
                        polar: true
                    },
                    subtitle: {
                        text: 'Monthly sales for XT Technology'
                    }
                });
            });


            const charts = Highcharts.chart('quarter_chart', {
                title: {
                    text: 'Summary earnings for all quarter'
                },
                credits: false,
                subtitle: {
                    text: 'Q1, Q2, Q3, Q4'
                },
                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                },
                series: [{
                    type: 'column',
                    colorByPoint: true,
                    data: str_reseller,
                    showInLegend: false
                }]
            });

            document.getElementById('q_1').addEventListener('click', () => {
                let str_q1 = [];
                $.each(myQ1, function(i, item) {
                    str_q1 = item.Q1.split(', ').map(Number);
                })

                charts.update({
                    title: {
                        text: 'Q1'
                    },
                    xAxis: {
                        categories: ['Jan', 'Feb', 'Mar']
                    },
                    series: [{
                        type: 'column',
                        colorByPoint: true,
                        data: str_q1,
                        showInLegend: false
                    }],
                    subtitle: {
                        text: 'Earnings in Q1, 2021'
                    }
                });
            });

            document.getElementById('q_2').addEventListener('click', () => {
                let str_q2 = [];
                $.each(myQ2, function(i, item) {
                    str_q2 = item.Q2.split(', ').map(Number);
                })

                charts.update({
                    title: {
                        text: 'Q2'
                    },
                    xAxis: {
                        categories: ['Apr', 'May', 'Jun']
                    },
                    series: [{
                        type: 'column',
                        colorByPoint: true,
                        data: str_q2,
                        showInLegend: false
                    }],
                    subtitle: {
                        text: 'Earnings in Q2, 2021'
                    }
                });
            });

            document.getElementById('q_3').addEventListener('click', () => {
                let str_q3 = [];
                $.each(myQ3, function(i, item) {
                    str_q3 = item.Q3.split(', ').map(Number);
                })

                charts.update({
                    title: {
                        text: 'Q3'
                    },
                    xAxis: {
                        categories: ['Jul', 'Aug', 'Sep']
                    },
                    series: [{
                        type: 'column',
                        colorByPoint: true,
                        data: str_q3,
                        showInLegend: false
                    }],
                    subtitle: {
                        text: 'Earnings in Q3, 2021'
                    }
                });
            });

            document.getElementById('q_4').addEventListener('click', () => {
                let str_q4 = [];
                $.each(myQ4, function(i, item) {
                    str_q4 = item.Q4.split(', ').map(Number);
                })

                charts.update({
                    title: {
                        text: 'Q4'
                    },
                    xAxis: {
                        categories: ['Oct', 'Nov', 'Dec']
                    },
                    series: [{
                        type: 'column',
                        colorByPoint: true,
                        data: str_q4,
                        showInLegend: false
                    }],
                    subtitle: {
                        text: 'Earnings in Q4, 2021'
                    }
                });
            });

            document.getElementById('all_quarter').addEventListener('click', () => {
                charts.update({
                    title: {
                        text: 'Summary earnings for all quarter'
                    },
                    xAxis: {
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                    },
                    series: [{
                        type: 'column',
                        colorByPoint: true,
                        data: str_reseller,
                        showInLegend: false
                    }],
                    subtitle: {
                        text: 'Q1, Q2, Q3, Q4'
                    }
                });
            });

        });
        @endcan

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#btn_update_rma").click(function (e) {
            e.preventDefault();

            if ($("#rma_tracking_no").val() === '' && $("#rma_resolution").val() === '') {
                Swal.fire(
                    'Null Value',
                    'Please input all fields with relevant information for Tracking No. and Remark.',
                    'error'
                )
            } else {

                var status = $("#floatingSelectStatus").val();
                var receive_at = $("#rma_receive_at").val();
                var remark = $("#rma_resolution").val();
                var tracking = $("#rma_tracking_no").val();
                var rma_id = $("#rma_no").val();
                var name = '{{ env('APP_URL') }}';

                $.ajax({
                    type: 'PATCH',
                    url: name+"/rma/rma-update/" + rma_id,
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
            }
        });

        $("#btn_apply_job").click(function (e) {

            e.preventDefault();

            var job_id = $("#job_id_apply").val();
            var job_u_id = $("#job_user_id_apply").val();
            var name = '{{ env('APP_URL') }}';

            $.ajax({
                type: 'PATCH',
                url: name+"/job/update/" + job_id,
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

        $("#btn_update_job").click(function (e) {

            e.preventDefault();

            var job_id = $("#job_id").val();
            var job_loc = $("#job_loc").val();
            var job_rate = $("#job_rate").val();
            var job_scope = $("#job_names").val();
            var job_type = $("#job_type_update").val();
            var name = '{{ env('APP_URL') }}';

            //alert(job_scope)

            $.ajax({
                type: 'PATCH',
                url: name+"/job/update-job-info/" + job_id,
                data: {
                    location: job_loc,
                    rate: job_rate,
                    scope: job_scope,
                    type: job_type
                },
                success: function (data) {
                    if (data['success'])
                        location.reload();
                    else
                        alert('EXISTING.')

                }
            });
        });

        $("#btn_update_job_status").click(function (e) {

            e.preventDefault();

            var job_id = $("#job_id_status_modal").val();
            var stats = $("#floatingUpdateStatus").val();
            var name = '{{ env('APP_URL') }}';

            console.log(job_id, stats)

            $.ajax({
                type: 'PATCH',
                url: name+"/job/update-job-status/" + job_id,
                data: {
                    status: stats,
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
            var rma_usr_address = button.data('myrmaaddress') // Extract info from data-* attributes

            var modal = $(this)
            modal.find('.modal-body #rma_no').val(rma_id);
            modal.find('.modal-body #rma_status').val(rma_status);
            modal.find('.modal-body #rma_reason').val(rma_reason);
            modal.find('.modal-body #rma_request_at').val(rma_request_at);
            modal.find('.modal-body #rma_tracking_no').val(rma_tracking_no);
            modal.find('.modal-body #rma_resolution').val(rma_resolution);
            modal.find('.modal-body #rma_receive_at').val(rma_receive);
            modal.find('.modal-body #floatingSelectStatus').val(rma_status);
            modal.find('.modal-body #rma_usr_address').val(rma_usr_address);

            document.getElementById("rma_no").innerText = "#" + rma_id;
            document.getElementById("rma_status").innerText = rma_status;

            if (document.getElementById("rma_status").value === 'Pending Receive') {
                $("#pending_receive_step").attr('class', 'step completed');
                $("#received_step").attr('class', 'step');
                $("#pending_checking_step").attr('class', 'step');
                $("#repair_step").attr('class', 'step');
                $("#shipped_step").attr('class', 'step');
            }

            if (document.getElementById("rma_status").value === 'Received') {
                $("#pending_receive_step").attr('class', 'step completed');
                $("#received_step").attr('class', 'step completed');
                $("#pending_checking_step").attr('class', 'step');
                $("#repair_step").attr('class', 'step');
                $("#shipped_step").attr('class', 'step');
            }

            if (document.getElementById("rma_status").value === 'Pending Checking') {
                $("#pending_receive_step").attr('class', 'step completed');
                $("#received_step").attr('class', 'step completed');
                $("#pending_checking_step").attr('class', 'step completed');
                $("#repair_step").attr('class', 'step');
                $("#shipped_step").attr('class', 'step');
            }

            if (document.getElementById("rma_status").value === 'Repair' || document.getElementById("rma_status").value === 'Replacement 1-1') {
                $("#pending_receive_step").attr('class', 'step completed');
                $("#received_step").attr('class', 'step completed');
                $("#pending_checking_step").attr('class', 'step completed');
                $("#repair_step").attr('class', 'step completed');
                $("#shipped_step").attr('class', 'step');
            }

            if (document.getElementById("rma_status").value === 'Shipped') {
                $("#pending_receive_step").attr('class', 'step completed');
                $("#received_step").attr('class', 'step completed');
                $("#pending_checking_step").attr('class', 'step completed');
                $("#repair_step").attr('class', 'step completed');
                $("#shipped_step").attr('class', 'step completed');
            }

            document.getElementById("rma_reason").innerText = rma_reason;
            document.getElementById("rma_request_at").innerText = rma_request_at;
            if (document.getElementById("rma_tracking_no").value === '') {
                document.getElementById("rma_tracking_no").innerText = '—';
            } else {
                document.getElementById("rma_tracking_no").innerText = rma_tracking_no;
            }

            if (document.getElementById("rma_resolution").value === '') {
                document.getElementById("rma_resolution").innerText = '—';
            } else {
                document.getElementById("rma_resolution").innerText = rma_resolution;
            }
            @can('is-reseller-distributor')
            document.getElementById("rma_receive_at").value = rma_receive;
            document.getElementById("floatingSelectStatus").value = rma_status;
            @endcan
            @can('is-user')
            document.getElementById("rma_usr_address").innerText = rma_usr_address;
            @endcan
            document.getElementById("myProdImg").src = "{{ \Storage::disk('s3')->url('product/') }}" + prod_pic;
        });

        $('#jobModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var job_person_in_charge = button.data('usrname')
            var job_email = button.data('usremail')
            var job_location = button.data('jobloc')
            var job_rate = button.data('jobsalary')
            var job_scope = button.data('jobname')
            var job_id = button.data('jobid')
            var job_usr_id = button.data('jobuserid')

            var modal = $(this)
            modal.find('.modal-body #pic_job_apply').val(job_person_in_charge);
            modal.find('.modal-body #job_email_apply').val(job_email);
            modal.find('.modal-body #job_loc_apply').val(job_location);
            modal.find('.modal-body #job_rate_apply').val(job_rate);
            modal.find('.modal-body #job_name_apply').val(job_scope);
            modal.find('.modal-body #job_id_apply').val(job_id);
            modal.find('.modal-body #job_user_id_apply').val(job_usr_id);

            document.getElementById("pic_job_apply").innerText = job_person_in_charge;
            document.getElementById("job_email_apply").innerText = job_email;
            document.getElementById("job_loc_apply").innerText = job_location;
            document.getElementById("job_rate_apply").innerText = 'RM ' + job_rate;
            document.getElementById("job_name_apply").innerText = job_scope;
            document.getElementById("job_id_apply").innerText = job_id;
            document.getElementById("job_user_id_apply").innerText = job_usr_id;
        });

        $('#updateJobModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var job_person_in_charge = button.data('usrname')
            var job_email = button.data('usremail')
            var job_location = button.data('jobloc')
            var job_rate = button.data('jobsalary')
            var job_scope = button.data('jobname')
            var job_id = button.data('jobid')
            var job_usr_id = button.data('jobuserid')
            var job_type = button.data('jobtype')

            var modal = $(this)
            modal.find('.modal-body #pic_job').val(job_person_in_charge);
            modal.find('.modal-body #job_email').val(job_email);
            modal.find('.modal-body #job_loc').val(job_location);
            modal.find('.modal-body #job_rate').val(job_rate);
            modal.find('.modal-body #job_names').val(job_scope);
            modal.find('.modal-body #job_id').val(job_id);
            modal.find('.modal-body #job_user_id').val(job_usr_id);
            modal.find('.modal-body #job_type_update').val(job_type);

            document.getElementById("pic_job").innerText = job_person_in_charge;
            document.getElementById("job_email").innerText = job_email;
            document.getElementById("job_loc").innerText = job_location;
            document.getElementById("job_rate").innerText = 'RM ' + job_rate;
            document.getElementById("job_names").innerText = job_scope;
            document.getElementById("job_id").innerText = job_id;
            document.getElementById("job_user_id").innerText = job_usr_id;
            document.getElementById("job_type_update").value = job_type;
        });

        $('#updateJobStatusModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var job_scope = button.data('jobname')
            var job_id = button.data('jobid')
            var job_status = button.data('jobstats')

            var modal = $(this)
            modal.find('.modal-body #job_name_status_modal').val(job_scope);
            modal.find('.modal-body #job_id_status_modal').val(job_id);
            modal.find('.modal-body #floatingUpdateStatus').val(job_status);

            document.getElementById("job_name_status_modal").innerText = job_scope;
            document.getElementById("job_id_status_modal").innerText = job_id;
            document.getElementById("floatingUpdateStatus").value = job_status;
        });

        function linkTrack(num) {
            TrackButton.track({
                tracking_no: num
            });
        }

        @guest
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
        @endguest
    </script>
@endsection
