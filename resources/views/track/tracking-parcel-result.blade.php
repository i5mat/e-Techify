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

    <h1 class="display-2 text-center">Tracking <img src="/image/parcel.png"/></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>Tracking status your parcel here!</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Someone famous in <cite title="Source Title">{{ $tracking_no }}</cite>
        </figcaption>
    </figure>

    <div class="card">
        <div class="card-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div id="tracking-pre"></div>
                        <div id="tracking">
                            @if($trackingStatusHeader == true)
                                <div class="text-center tracking-status-delivered">
                                    <p class="tracking-status text-tight">Delivered</p>
                                </div>
                            @else
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
                <a href="{{ route('track.new.parcel') }}">
                    <button class="btn btn-warning" type="button" style="width: 100%">Back</button>
                </a>
            </div>
        </div>
    </div>

    <script>
        feather.replace()
    </script>
@endsection
