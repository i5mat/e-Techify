@extends('templates.main')

@section('content')
    <style>
        ul.timeline {
            list-style-type: none;
            position: relative;
        }
        ul.timeline:before {
            content: ' ';
            background: #d4d9df;
            display: inline-block;
            position: absolute;
            left: 29px;
            width: 2px;
            height: 100%;
            z-index: 400;
        }
        ul.timeline > li {
            margin: 20px 0;
            padding-left: 20px;
        }
        ul.timeline > li:before {
            content: ' ';
            background: white;
            display: inline-block;
            position: absolute;
            border-radius: 50%;
            border: 3px solid #22c0e8;
            left: 20px;
            width: 20px;
            height: 20px;
            z-index: 400;
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

            <div class="container mt-5 mb-5">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <h4>Tracking Status</h4>
                        <ul class="timeline">
                            @foreach($trackingStatus as $track_stats)
                                <li>
                                    <a><b>{{ $track_stats->current_status }}</b></a>
                                    <a href="#" class="float-right" style="margin-left: 48%">{{ date('Y-m-d H:i A', strtotime($track_stats->created_at)) }}</a>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque scelerisque diam non nisi semper, et elementum lorem ornare. Maecenas placerat facilisis mollis. Duis sagittis ligula in sodales vehicula....</p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('track.insert.trackparcel', $recipientInfo->order_id) }}">
                @csrf
                <dl class="row">
                    <dt class="col-sm-3">Tracking Status</dt>
                    <dd class="col-sm-9">
                        <input type="text" class="form-control" id="update_tracking" name="update_tracking" placeholder="Insert Current Status">
                    </dd>
                </dl>
                <div class="text-center">
                    <p class="lead">
                        <button type="submit" class="btn btn-warning" style="width: 100%">Submit</button>
                    </p>
                </div>
            </form>

        </div>
    </div>
@endsection
