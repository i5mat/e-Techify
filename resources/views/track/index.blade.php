@extends('templates.main')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/css/ol.css" type="text/css">
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
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

        #map {
            height: 300px;
            width: 100%;
        }
    </style>

    <h1 class="display-2 text-center">Tracking Status <img src="/image/sn.png"/></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>Tracking status for #{{ $recipientInfo->tracking_num }}, Order ID {{ $recipientInfo->order_id }}</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Prepared by <cite title="Source Title">Wan Ismat</cite>
        </figcaption>
    </figure>

    <div class="card" style="margin-bottom: 10px">
        <div id="floating-panel" style="display: none;">
            <b>Mode of Travel: </b>
            <select id="mode">
                <option value="DRIVING">Driving</option>
                <option value="WALKING">Walking</option>
                <option value="BICYCLING">Bicycling</option>
                <option value="TRANSIT">Transit</option>
            </select>
        </div>
        <div id="map"></div>

        <div class="card-body" >
            <dl class="row">
                <dt class="col-sm-3">Name & Phone No.</dt>
                <dd class="col-sm-9" id="user_name"><b>{{ $recipientInfo->name }} +(60) {{ $recipientInfo->phone_no }}</b></dd>

                <dt class="col-sm-3">Address</dt>
                <dd class="col-sm-9">
                    <p id="address">{{ $recipientInfo->address }}</p>

                <dt class="col-sm-3">Tracking No.</dt>
                <dd class="col-sm-9">{{ $recipientInfo->tracking_num }} <span class="badge bg-warning text-dark">System Tracking No.</span></dd>

                @if(isset($recipientInfo->courier_tracking_no))
                    <dt class="col-sm-3">Courier Tracking No.</dt>
                    <dd class="col-sm-9">
                        <a class="btn btn-outline-dark rounded-pill" style="font-size: 15px"
                           onclick="linkTrack(this.innerText)">{{ $recipientInfo->courier_tracking_no }}</a>
                    </dd>
                @endif

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
                            @elseif ($recipientInfo->order_status == 'Cancelled')
                                <div class="text-center tracking-status-error">
                                    <p class="tracking-status text-tight">Cancelled</p>
                                </div>
                            @endif
                            <div class="tracking-list" id="tracking_list">
                                @foreach($trackingStatus as $track_stats)
                                    <div class="tracking-item">
                                        @if($track_stats->current_status == 'Confirmed Order')
                                            <div class="tracking-icon status-inforeceived">
                                                <i data-feather="clipboard" style="margin-bottom: 5px"></i>
                                            </div>
                                            <div class="tracking-date">{{ date('d M, Y', strtotime($track_stats->created_at)) }}<span>{{ date('H:i A', strtotime($track_stats->created_at)) }}</span></div>
                                            <div class="tracking-content">{{ $track_stats->current_status }}<span>Order is confirmed</span></div>
                                        @elseif ($track_stats->current_status == 'Processing Order')
                                            <div class="tracking-icon status-intransit">
                                                <i class="fa fa-minus" style="margin-bottom: 5px"></i>
                                            </div>
                                            <div class="tracking-date">{{ date('d M, Y', strtotime($track_stats->created_at)) }}<span>{{ date('H:i A', strtotime($track_stats->created_at)) }}</span></div>
                                            <div class="tracking-content">{{ $track_stats->current_status }}<span>Order is being processed</span></div>
                                        @elseif ($track_stats->current_status == 'Quality Check')
                                            <div class="tracking-icon status-exception">
                                                <i class="fa fa-check-circle" style="margin-bottom: 5px"></i>
                                            </div>
                                            <div class="tracking-date">{{ date('d M, Y', strtotime($track_stats->created_at)) }}<span>{{ date('H:i A', strtotime($track_stats->created_at)) }}</span></div>
                                            <div class="tracking-content">{{ $track_stats->current_status }}<span>Product is being inspected</span></div>
                                        @elseif ($track_stats->current_status == 'Product Dispatched')
                                            <div class="tracking-icon status-outfordelivery">
                                                <i class="fa fa-truck" style="margin-bottom: 5px"></i>
                                            </div>
                                            <div class="tracking-date">{{ date('d M, Y', strtotime($track_stats->created_at)) }}<span>{{ date('H:i A', strtotime($track_stats->created_at)) }}</span></div>
                                            <div class="tracking-content">{{ $track_stats->current_status }}<span>Your order is getting ready to be dispatched out to the couriers</span></div>
                                        @elseif ($track_stats->current_status == 'Product Delivered')
                                            <div class="tracking-icon status-delivered">
                                                <i data-feather="check" style="margin-bottom: 5px"></i>
                                            </div>
                                            <div class="tracking-date">{{ date('d M, Y', strtotime($track_stats->created_at)) }}<span>{{ date('H:i A', strtotime($track_stats->created_at)) }}</span></div>
                                            <div class="tracking-content">{{ $track_stats->current_status }}<span>Your items have been handed over to respective courier, please check in order details for "Courier Tracking Number" 🚚</span></div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form>
                @csrf
                @if($recipientInfo->order_status == 'Delivered')
                    <h1 class="text-center display-6">You can check your parcel status at "Courier Tracking Number" 🚚</h1>
                @elseif ($recipientInfo->order_status == 'To Ship')
                    @can('is-reseller')
                    <dl class="row">
                        <dt class="col-sm-3">Tracking Status</dt>
                        <dd class="col-sm-9" id="tracking_status_input">
                            <input readonly type="text" class="form-control" id="update_tracking" name="update_tracking" placeholder="Insert Current Status">
                            @if($track_stats->current_status == 'Confirmed Order')
                                <button type="button" name="processing_order_btn" id="processing_order_btn" class="btn btn-outline-warning" style="margin-top: 5px">Processing Order</button>
                            @elseif ($track_stats->current_status == 'Processing Order')
                                <button type="button" name="qc_btn" id="qc_btn" class="btn btn-outline-warning" style="margin-top: 5px">Quality Check</button>
                            @elseif ($track_stats->current_status == 'Quality Check')
                                <button type="button" name="dispatched_btn" id="dispatched_btn" class="btn btn-outline-warning" style="margin-top: 5px">Product Dispatched</button>
                            @elseif ($track_stats->current_status == 'Product Dispatched')
                                <input type="text" class="form-control mt-1" id="courier_track_no" name="courier_track_no" placeholder="Insert Tracking No.">
                                <button type="button" name="delivered_btn" id="delivered_btn" class="btn btn-outline-warning" style="margin-top: 5px">Product Delivered</button>
                            @endif
                        </dd>
                    </dl>
                    <div class="text-center">
                        <p class="lead">
                            <button type="button" class="btn btn-warning" style="width: 100%" id="btn_sbmit" name="btn_sbmit">Submit</button>
                        </p>
                    </div>
                    @endcan
                @endif
            </form>
            @can('is-user')
                <a href="/order/orders" class="btn btn-warning" style="width: 100%">Back</a>
            @endcan
        </div>
    </div>

    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA7jC89-qmnCWo2FSQy8zg0LxOvNlncp9I&callback=initMap&libraries=&v=weekly"
        async
    ></script>
    <script src="//www.tracking.my/track-button.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/build/ol.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        feather.replace()

        function initMap() {
            const directionsRenderer = new google.maps.DirectionsRenderer();
            const directionsService = new google.maps.DirectionsService();
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 14,
                center: { lat: 3.22097, lng: 101.724 },
            });
            directionsRenderer.setMap(map);
            calculateAndDisplayRoute(directionsService, directionsRenderer);
        }

        function calculateAndDisplayRoute(directionsService, directionsRenderer) {
            const selectedMode = document.getElementById("mode").value;
            directionsService.route(
                {
                    origin: { lat: 3.22097, lng: 101.724 },
                    destination: { lat: {{ $recipientInfo->longitude }}, lng: {{ $recipientInfo->latitude }} },
                    // Note that Javascript allows us to access the constant
                    // using square brackets and a string value as its
                    // "property."
                    travelMode: google.maps.TravelMode[selectedMode],
                },
                (response, status) => {
                    if (status == "OK") {
                        directionsRenderer.setDirections(response);
                    } else {
                        window.alert("Directions request failed due to " + status);
                    }
                }
            );
        }

        function linkTrack(num) {
            TrackButton.track({
                tracking_no: num
            });
        }

        $(document).ready(function(){
            $("#update_tracking").click(function(){
                document.getElementById('update_tracking').value = 'Click on the button, not me! :/';
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

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#btn_sbmit").click(function(e){

            if ($("#update_tracking").val() === '' || $("#courier_track_no").val() === '') {
                Swal.fire(
                    'Input NULL',
                    'Please input all fields with relevant information',
                    'error'
                )
            } else {
                e.preventDefault();

                var tracking = $("#update_tracking").val();
                var courier_tracking = $("#courier_track_no").val();

                $.ajax({
                    type:'POST',
                    url:"{{ route('track.insert.trackparcel', $recipientInfo->order_id) }}",
                    data:{update_tracking:tracking, courier:courier_tracking},
                    success:function(data){
                        if ( data['success'] ) {
                            if (data.latest_stats == 'Processing Order') {
                                document.getElementById("tracking_list").innerHTML += '<div class="tracking-item">'+
                                    '<div class="tracking-icon status-intransit"><i class="fa fa-minus" style="margin-bottom: 5px"></i></div>'+
                                    '<div class="tracking-date">{{ date('d M, Y', strtotime(now())) }}<span>{{ date('H:i A', strtotime(now())) }}</span></div>'+
                                    '<div class="tracking-content">Processing Order<span>Order is being processed</span></div>';

                                $("#update_tracking").val("Quality Check");
                                $("#processing_order_btn").text("Quality Check");

                                $("#processing_order_btn").attr('name', "qc_btn");
                                $("#processing_order_btn").attr('id', "qc_btn");
                            }
                            else if (data.latest_stats == 'Quality Check') {
                                document.getElementById("tracking_list").innerHTML += '<div class="tracking-item">'+
                                    '<div class="tracking-icon status-exception"><i class="fa fa-check-circle" style="margin-bottom: 5px"></i></div>'+
                                    '<div class="tracking-date">{{ date('d M, Y', strtotime(now())) }}<span>{{ date('H:i A', strtotime(now())) }}</span></div>'+
                                    '<div class="tracking-content">Quality Check<span>Product is being inspected</span></div>';

                                $("#update_tracking").val("Product Dispatched");
                                $("#qc_btn").text("Product Dispatched");

                                $("#qc_btn").attr('name', "dispatched_btn");
                                $("#qc_btn").attr('id', "dispatched_btn");
                            }
                            else if (data.latest_stats == 'Product Dispatched') {
                                document.getElementById("tracking_list").innerHTML += '<div class="tracking-item">'+
                                    '<div class="tracking-icon status-outfordelivery"><i class="fa fa-truck" style="margin-bottom: 5px"></i></div>'+
                                    '<div class="tracking-date">{{ date('d M, Y', strtotime(now())) }}<span>{{ date('H:i A', strtotime(now())) }}</span></div>'+
                                    '<div class="tracking-content">Product Dispatched<span>Your order is getting ready to be dispatched out to the couriers</span></div>';

                                document.getElementById("tracking_status_input").innerHTML +=
                                    '<input type="text" class="form-control mt-1" id="courier_track_no" name="courier_track_no" placeholder="Insert Tracking No.">';

                                $("#update_tracking").val("Product Delivered");
                                $("#dispatched_btn").text("Product Delivered");

                                $("#dispatched_btn").attr('name', "delivered_btn");
                                $("#dispatched_btn").attr('id', "delivered_btn");
                                $("#delivered_btn").attr("hidden", "true");
                            }
                            else if (data.latest_stats == 'Product Delivered') {
                                location.reload();
                            }
                        }
                            console.log(data.latest_stats)

                    }
                });
            }
        });

    </script>
@endsection
