@extends('templates.main')

@section('content')
    <style>
        .tracking-detail {
            padding:3rem 0
        }
        #tracking {
            margin-bottom:1rem
        }
        [class*=tracking-status-] p {
            margin:0;
            font-size:1.1rem;
            color:#fff;
            text-transform:uppercase;
            text-align:center
        }
        [class*=tracking-status-] {
            padding:1.6rem 0
        }
        .tracking-status-intransit {
            background-color:#65aee0
        }
        .tracking-status-outfordelivery {
            background-color:#f5a551
        }
        .tracking-status-deliveryoffice {
            background-color:#f7dc6f
        }
        .tracking-status-delivered {
            background-color:#4cbb87
        }
        .tracking-status-attemptfail {
            background-color:#b789c7
        }
        .tracking-status-error,.tracking-status-exception {
            background-color:#d26759
        }
        .tracking-status-expired {
            background-color:#616e7d
        }
        .tracking-status-pending {
            background-color:#ccc
        }
        .tracking-status-inforeceived {
            background-color:#214977
        }
        .tracking-list {
            border:1px solid #e5e5e5
        }
        .tracking-item {
            border-left:1px solid #e5e5e5;
            position:relative;
            padding:2rem 1.5rem .5rem 2.5rem;
            font-size:.9rem;
            margin-left:3rem;
            min-height:5rem
        }
        .tracking-item:last-child {
            padding-bottom:4rem
        }
        .tracking-item .tracking-date {
            margin-bottom:.5rem
        }
        .tracking-item .tracking-date span {
            color:#888;
            font-size:85%;
            padding-left:.4rem
        }
        .tracking-item .tracking-content {
            padding:.5rem .8rem;
            background-color:#f4f4f4;
            border-radius:.5rem
        }
        .tracking-item .tracking-content span {
            display:block;
            color:#888;
            font-size:85%
        }
        .tracking-item .tracking-icon {
            line-height:2.6rem;
            position:absolute;
            left:-1.3rem;
            width:2.6rem;
            height:2.6rem;
            text-align:center;
            border-radius:50%;
            font-size:1.1rem;
            background-color:#fff;
            color:#fff
        }
        .tracking-item .tracking-icon.status-sponsored {
            background-color:#f68
        }
        .tracking-item .tracking-icon.status-delivered {
            background-color:#4cbb87
        }
        .tracking-item .tracking-icon.status-outfordelivery {
            background-color:#f5a551
        }
        .tracking-item .tracking-icon.status-deliveryoffice {
            background-color:#f7dc6f
        }
        .tracking-item .tracking-icon.status-attemptfail {
            background-color:#b789c7
        }
        .tracking-item .tracking-icon.status-exception {
            background-color:#d26759
        }
        .tracking-item .tracking-icon.status-inforeceived {
            background-color:#214977
        }
        .tracking-item .tracking-icon.status-intransit {
            color:#e5e5e5;
            border:1px solid #e5e5e5;
            font-size:.6rem
        }
        @media(min-width:992px) {
            .tracking-item {
                margin-left:10rem
            }
            .tracking-item .tracking-date {
                position:absolute;
                left:-10rem;
                width:7.5rem;
                text-align:right
            }
            .tracking-item .tracking-date span {
                display:block
            }
            .tracking-item .tracking-content {
                padding:0;
                background-color:transparent
            }
        }
    </style>

    <h1 class="display-2 text-center">Tracking Status <img src="/image/sn.png"/></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>Tracking status for #{{ $recipientInfo->tracking_num }}, Order ID {{ $recipientInfo->order_id }}</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Someone famous in <cite title="Source Title">Source Title</cite>
        </figcaption>
    </figure>

    <div class="card" style="margin-bottom: 10px">
        <div class="card-body" >
            <dl class="row">
                <dt class="col-sm-3">Name & Phone No.</dt>
                <dd class="col-sm-9" id="user_name"><b>{{ $recipientInfo->name }} +(60) {{ $recipientInfo->phone_no }}</b></dd>

                <dt class="col-sm-3">Address</dt>
                <dd class="col-sm-9">
                    <p id="address">{{ $recipientInfo->address }}</p>

                <dt class="col-sm-3">Tracking No.</dt>
                <dd class="col-sm-9">{{ $recipientInfo->tracking_num }} <span class="badge bg-warning text-dark">XT Express</span></dd>

                <dt class="col-sm-3">Tracking Status</dt>
                <dd class="col-sm-9">{{ $recipientInfo->order_status }}</dd>

                <dt class="col-sm-3">Barcode</dt>
                <dd class="col-sm-9">{!! DNS1D::getBarcodeSVG($recipientInfo->tracking_num, "C39", 1, 50, '#2A3239') !!} </dd>

                <dt class="col-sm-3">QR Code</dt>
                <dd class="col-sm-9">{!! DNS2D::getBarcodeHTML($recipientInfo->tracking_num, 'QRCODE', 5, 5) !!} </dd>
            </dl>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div id="tracking-pre"></div>
                        <div id="tracking">
                            @if($recipientInfo->order_status == 'Delivered')
                                <div class="text-center tracking-status-delivered">
                                    <p class="tracking-status text-tight">Delivered</p>
                                </div>
                            @elseif ($recipientInfo->order_status == 'To Ship')
                                <div class="text-center tracking-status-intransit">
                                    <p class="tracking-status text-tight">in transit</p>
                                </div>
                            @endif
                            <div class="tracking-list">
                                @foreach($trackingStatus as $track_stats)
                                <div class="tracking-item">
                                    @if($track_stats->current_status == 'Confirmed Order')
                                        <div class="tracking-icon status-inforeceived">
                                            <i data-feather="clipboard" style="margin-bottom: 5px"></i>
                                        </div>
                                    @elseif ($track_stats->current_status == 'Processing Order')
                                        <div class="tracking-icon status-intransit">
                                            <i data-feather="minus" style="margin-bottom: 5px"></i>
                                        </div>
                                    @elseif ($track_stats->current_status == 'Quality Check')
                                        <div class="tracking-icon status-exception">
                                            <i data-feather="check-circle" style="margin-bottom: 5px"></i>
                                        </div>
                                    @elseif ($track_stats->current_status == 'Product Dispatched')
                                        <div class="tracking-icon status-outfordelivery">
                                            <i data-feather="truck" style="margin-bottom: 5px"></i>
                                        </div>
                                    @elseif ($track_stats->current_status == 'Product Delivered')
                                        <div class="tracking-icon status-delivered">
                                            <i data-feather="check" style="margin-bottom: 5px"></i>
                                        </div>
                                    @endif
                                    <div class="tracking-date">{{ date('d M, Y', strtotime($track_stats->created_at)) }}<span>{{ date('H:i A', strtotime($track_stats->created_at)) }}</span></div>
                                    <div class="tracking-content">{{ $track_stats->current_status }}<span>KUALA LUMPUR (XT WAREHOUSE), MALAYSIA</span></div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('track.insert.trackparcel', $recipientInfo->order_id) }}">
                @csrf
                @if($recipientInfo->order_status == 'Delivered')
                    <h1 class="text-center display-6">Thank You! Enjoy your new item Bangsawan! #BangsawanForever</h1>
                @elseif ($recipientInfo->order_status == 'To Ship')
                    <dl class="row">
                        <dt class="col-sm-3">Tracking Status</dt>
                        <dd class="col-sm-9">
                            <input readonly type="text" class="form-control" id="update_tracking" name="update_tracking" placeholder="Insert Current Status">
                            @if($track_stats->current_status == 'Confirmed Order')
                                <button type="button" name="processing_order_btn" id="processing_order_btn" class="btn btn-outline-warning" style="margin-top: 5px">Processing Order</button>
                            @elseif ($track_stats->current_status == 'Processing Order')
                                <button type="button" name="qc_btn" id="qc_btn" class="btn btn-outline-warning" style="margin-top: 5px">Quality Check</button>
                            @elseif ($track_stats->current_status == 'Quality Check')
                                <button type="button" name="dispatched_btn" id="dispatched_btn" class="btn btn-outline-warning" style="margin-top: 5px">Product Dispatched</button>
                            @elseif ($track_stats->current_status == 'Product Dispatched')
                                <button type="button" name="delivered_btn" id="delivered_btn" class="btn btn-outline-warning" style="margin-top: 5px">Product Delivered</button>
                            @endif
{{--                            <button type="button" name="confirm_order_btn" id="confirm_order_btn" class="btn btn-outline-warning" style="margin-top: 5px">Confirmed Order</button>--}}
{{--                            <button type="button" name="processing_order_btn" id="processing_order_btn" class="btn btn-outline-warning" style="margin-top: 5px">Processing Order</button>--}}
{{--                            <button type="button" name="qc_btn" id="qc_btn" class="btn btn-outline-warning" style="margin-top: 5px">Quality Check</button>--}}
{{--                            <button type="button" name="dispatched_btn" id="dispatched_btn" class="btn btn-outline-warning" style="margin-top: 5px">Product Dispatched</button>--}}
{{--                            <button type="button" name="delivered_btn" id="delivered_btn" class="btn btn-outline-warning" style="margin-top: 5px">Product Delivered</button>--}}
                        </dd>
                    </dl>
                    <div class="text-center">
                        <p class="lead">
                            <button type="submit" class="btn btn-warning" style="width: 100%">Submit</button>
                        </p>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <script>
        feather.replace()

        $(document).ready(function(){
            $("#update_tracking").click(function(){
                document.getElementById('update_tracking').value = 'Confirmed Order';
            });
            $("#processing_order_btn").click(function(){
                document.getElementById('update_tracking').value = 'Processing Order';
            });
            $("#qc_btn").click(function(){
                document.getElementById('update_tracking').value = 'Quality Check';
            });
            $("#dispatched_btn").click(function(){
                document.getElementById('update_tracking').value = 'Product Dispatched';
            });
            $("#delivered_btn").click(function(){
                document.getElementById('update_tracking').value = 'Product Delivered';
            });
        });
    </script>
@endsection
