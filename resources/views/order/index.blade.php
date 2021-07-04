@extends('templates.main')

@section('content')
    <h1 class="display-2 text-center">Order <img src="/image/received.png"/></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>Manage orders.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Prepared by <cite title="Source Title">Wan Ismat</cite>
        </figcaption>
    </figure>

    <div class="card">
        <div class="container mt-4">
            @can('is-reseller')
                <div class="row text-center">
                    <div class="col">
                        <i data-feather="eye"></i>
                        <span class="ms-5">View order in detail</span>
                    </div>
                    <div class="col">
                        <i data-feather="file-text"></i>
                        <span class="ms-5">Get receipt</span>
                    </div>
                    <div class="col">
                        <i data-feather="truck"></i>
                        <span class="ms-5">Check tracking status</span>
                    </div>
                </div>
                <div class="row text-center mt-3 mb-3">
                    <div class="col">
                        <i data-feather="printer"></i>
                        <span class="ms-5">Print AWB</span>
                    </div>
                    <div class="col">
                        <i data-feather="x-circle"></i>
                        <span class="ms-5">Cancel order</span>
                    </div>
                    <div class="col">
                        <i data-feather="upload"></i>
                        <span class="ms-5">Insert SN each product</span>
                    </div>
                </div>
            @endcan
            @can('is-user')
                <div class="row text-center">
                    <div class="col">
                        <i data-feather="eye"></i>
                        <span class="ms-5">View order in detail</span>
                    </div>
                    <div class="col">
                        <i data-feather="file-text"></i>
                        <span class="ms-5">Get receipt</span>
                    </div>
                    <div class="col">
                        <i data-feather="truck"></i>
                        <span class="ms-5">Check tracking status</span>
                    </div>
                    <div class="col">
                        <i data-feather="x-circle"></i>
                        <span class="ms-5">Cancel order</span>
                    </div>
                </div>
            @endcan
        </div>

        <div class="card-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active font-monospace" id="home-tab" data-toggle="tab" href="#home" role="tab"
                       aria-controls="home" aria-selected="true">To Ship <span class="badge bg-secondary ms-2" style="color: white">{{ $to_ship->count() }}</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-monospace" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                       aria-controls="profile" aria-selected="false">Completed <span class="badge bg-secondary ms-2" style="color: white">{{ $delivered->count() }}</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-monospace" id="messages-tab" data-toggle="tab" href="#messages" role="tab"
                       aria-controls="messages" aria-selected="false">Cancelled <span class="badge bg-secondary ms-2" style="color: white">{{ $cancelled->count() }}</span></a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    @if($to_ship->count() <= 0)
                        <div class="row">
                            <img class="mt-2" src="/image/no-toship.png" style="width: 400px; height: 400px; display: block; margin-left: auto; margin-right: auto">
                            <h1 class="text-center display-6">
                                To ship empty.
                            </h1>
                        </div>
                    @else
                        <div class="row g-1">
                            @foreach($to_ship as $i)
                                <div @if($to_ship->count() == 1) class="col-md-12" @else class="col-md-6" @endif>
                                    <div class="card p-2 border-5 border-bottom border-primary" style="border: none">
                                        <div class="text-right badge bg-primary"><small class="lead" style="color: white">Order
                                                #{{ $i->id }}</small></div>
                                        <div class="text-center mt-2 p-3">
                                            <img src="/image/XT-logo.png" width="100" height="65"/>
                                            <span class="d-block font-weight-bold"></span>
                                            by <span class="display-6">{{ $i->name }}</span>
                                            <hr>
                                            <span class="badge bg-primary" style="color: white">{{ $i->order_status }}</span>
                                            <div class="d-flex flex-row align-items-center justify-content-center">
                                                <b>{{ date('jS F Y h:i A', strtotime($i->created_at)) }}</b>                                            </div>
                                            <div class="d-flex justify-content-between mt-3">

                                                @can('is-user')
                                                <span>
                                                    <a href="{{ route('order.index.orderdetails', $i->id) }}"><i
                                                        data-feather="eye"></i></a>
                                                </span>
                                                <span>
                                                    <a href="{{ route('order.purchase.receipt', $i->id) }}" target="_blank"><i
                                                        data-feather="file-text"></i></a>
                                                </span>
                                                <span>
                                                <a href="{{ route('track.index.trackparcel', $i->id) }}"><i
                                                        data-feather="truck"></i></a>
                                                </span>
                                                    <form id="cancel-order-{{ $i->id }}"
                                                          action="{{ route('order.order.cancel', $i->id) }}" method="POST"
                                                          style="display: none">
                                                        @csrf
                                                        @method('PATCH')
                                                    </form>
                                                    <button
                                                        @if(App\Models\Tracking::where('order_id', '=', $i->id)->where('current_status', '=', 'Processing Order')->exists()) disabled
                                                        @endif style="border: none" class="btn btn-sm btn-outline-danger"
                                                        onclick="event.preventDefault(); document.getElementById('cancel-order-{{ $i->id }}').submit()">
                                                        <i data-feather="x-circle"></i></button>
                                                @endcan
                                                @can('is-reseller')
                                                    <span>
                                                <a href="{{ route('track.index.trackparcel', $i->id) }}"><i
                                                        data-feather="truck"></i></a>
                                                </span>
                                                    <span>
                                                <a href="{{ route('order.index.orderdetails', $i->id) }}"><i
                                                        data-feather="eye"></i></a>
                                                </span>
                                                    <span>
                                                <a href="{{ route('order.purchase.receipt', $i->id) }}" target="_blank"><i
                                                        data-feather="file-text"></i></a>
                                                </span>
                                                    <span>
                                                <a href="{{ route('order.purchase.insertsn', $i->id) }}"><i
                                                        data-feather="upload"></i></a>
                                                </span>

                                                    <form id="get-awb-order-{{ $i->id }}"
                                                          action="{{ route('order.purchase.awb', $i->id) }}" method="POST"
                                                          style="display: none">
                                                        @csrf
                                                    </form>
                                                    <button style="border: none" class="btn btn-sm btn-outline-dark"
                                                            onclick="event.preventDefault(); document.getElementById('get-awb-order-{{ $i->id }}').submit()">
                                                        <i data-feather="printer"></i></button>

                                                    <form id="cancel-order-{{ $i->id }}"
                                                          action="{{ route('order.order.cancel', $i->id) }}" method="POST"
                                                          style="display: none">
                                                        @csrf
                                                        @method('PATCH')
                                                    </form>
                                                    <button style="border: none" class="btn btn-sm btn-outline-danger"
                                                            onclick="event.preventDefault(); document.getElementById('cancel-order-{{ $i->id }}').submit()">
                                                        <i data-feather="x-circle"></i></button>
                                                @endcan

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    @if($delivered->count() <= 0)
                        <div class="row">
                            <img class="mt-2" src="/image/no-completed.png" style="width: 400px; height: 400px; display: block; margin-left: auto; margin-right: auto">
                            <h1 class="text-center display-6">
                                Delivered empty.
                            </h1>
                        </div>
                    @else
                        <div class="row g-1">
                            @foreach($delivered as $i)
                                <div @if($delivered->count() == 1) class="col-md-12" @else class="col-md-6" @endif>
                                    <div class="card p-2 border-5 border-bottom border-success" style="border: none">
                                        <div class="text-right badge bg-success"><small class="lead" style="color: white">Order
                                                #{{ $i->id }}</small></div>
                                        <div class="text-center mt-2 p-3">
                                            <img src="/image/XT-logo.png" width="100" height="65"/>
                                            <span class="d-block font-weight-bold"></span>
                                            by <span class="display-6">{{ $i->name }}</span>
                                            <hr>
                                            <span class="badge bg-success" style="color: white">{{ $i->order_status }}</span>
                                            <div class="d-flex flex-row align-items-center justify-content-center">
                                                <b>{{ date('jS F Y h:i A', strtotime($i->created_at)) }}</b>                                            </div>
                                            <div class="d-flex justify-content-between mt-3">
                                                @can('is-user')
                                                    <span>
                                                    <a href="{{ route('order.index.orderdetails', $i->id) }}"><i
                                                            data-feather="eye"></i>
                                                    </a>
                                                </span>
                                                    <span>
                                                    <a href="{{ route('order.purchase.receipt', $i->id) }}" target="_blank"><i
                                                            data-feather="file-text"></i>
                                                    </a>
                                                </span>
                                                    <span>
                                                    <a href="{{ route('track.index.trackparcel', $i->id) }}"><i
                                                            data-feather="truck"></i>
                                                    </a>
                                                </span>
                                                    <form id="cancel-order-{{ $i->id }}"
                                                          action="{{ route('order.order.cancel', $i->id) }}" method="POST"
                                                          style="display: none">
                                                        @csrf
                                                        @method('PATCH')
                                                    </form>
                                                    <button
                                                        @if(App\Models\Tracking::where('order_id', '=', $i->id)->where('current_status', '=', 'Processing Order')->exists()) disabled
                                                        @endif style="border: none" class="btn btn-sm btn-outline-danger"
                                                        onclick="event.preventDefault(); document.getElementById('cancel-order-{{ $i->id }}').submit()">
                                                        <i data-feather="x-circle"></i></button>
                                                @endcan
                                                @can('is-reseller')
                                                    <span>
                                                    <a href="{{ route('track.index.trackparcel', $i->id) }}">
                                                        <i data-feather="truck"></i>
                                                    </a>
                                                </span>
                                                    <span>
                                                    <a href="{{ route('order.index.orderdetails', $i->id) }}">
                                                        <i data-feather="eye"></i>
                                                    </a>
                                                </span>
                                                    <span>
                                                    <a href="{{ route('order.purchase.receipt', $i->id) }}" target="_blank">
                                                        <i data-feather="file-text"></i>
                                                    </a>
                                                </span>
                                                    <span>
                                                    <a href="{{ route('order.purchase.insertsn', $i->id) }}">
                                                        <i data-feather="upload"></i>
                                                    </a>
                                                </span>

                                                    <form id="get-awb-order-{{ $i->id }}"
                                                          action="{{ route('order.purchase.awb', $i->id) }}" method="POST"
                                                          style="display: none">
                                                        @csrf
                                                    </form>

                                                    <button disabled style="border: none" class="btn btn-sm btn-outline-dark"
                                                            onclick="event.preventDefault(); document.getElementById('get-awb-order-{{ $i->id }}').submit()">
                                                        <i data-feather="printer"></i>
                                                    </button>

                                                    <form id="cancel-order-{{ $i->id }}"
                                                          action="{{ route('order.order.cancel', $i->id) }}" method="POST"
                                                          style="display: none">
                                                        @csrf
                                                        @method('PATCH')
                                                    </form>

                                                    <button disabled style="border: none" class="btn btn-sm btn-outline-danger"
                                                            onclick="event.preventDefault(); document.getElementById('cancel-order-{{ $i->id }}').submit()">
                                                        <i data-feather="x-circle"></i>
                                                    </button>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                    @if($cancelled->count() <= 0)
                        <div class="row">
                            <img class="mt-2" src="/image/no-cancel.png" style="width: 400px; height: 400px; display: block; margin-left: auto; margin-right: auto">
                            <h1 class="text-center display-6">
                                Cancelled empty.
                            </h1>
                        </div>
                    @else
                        <div class="row g-1">
                            @foreach($cancelled as $i)
                                <div @if($cancelled->count() == 1) class="col-md-12" @else class="col-md-6" @endif>
                                    <div class="card p-2 border-5 border-bottom border-danger" style="border: none">
                                        <div class="text-right badge bg-danger"><small class="lead" style="color: white">Order
                                                #{{ $i->id }}</small></div>
                                        <div class="text-center mt-2 p-3">
                                            <img src="/image/XT-logo.png" width="100" height="65"/>
                                            <span class="d-block font-weight-bold"></span>
                                            by <span class="display-6">{{ $i->name }}</span>
                                            <hr>
                                            <span class="badge bg-danger" style="color: white">{{ $i->order_status }}</span>
                                            <div class="d-flex flex-row align-items-center justify-content-center">
                                                <b>{{ date('jS F Y h:i A', strtotime($i->created_at)) }}</b>
                                            </div>
                                            <div class="d-flex justify-content-between mt-3">

                                                @can('is-user')
                                                    <span>
                                                    <a href="{{ route('order.index.orderdetails', $i->id) }}"><i
                                                            data-feather="eye"></i>
                                                    </a>
                                                </span>
                                                    <span>
                                                    <a href="{{ route('track.index.trackparcel', $i->id) }}"><i
                                                            data-feather="truck"></i>
                                                    </a>
                                                </span>
                                                @endcan
                                                @can('is-reseller')
                                                    <span>
                                                <a href="{{ route('track.index.trackparcel', $i->id) }}"><i
                                                        data-feather="truck"></i></a>
                                            </span>
                                                    <span>
                                                <a href="{{ route('order.index.orderdetails', $i->id) }}"><i
                                                        data-feather="eye"></i></a>
                                            </span>
                                                    <span>
                                                <a href="{{ route('order.purchase.receipt', $i->id) }}" target="_blank"><i
                                                        data-feather="file-text"></i></a>
                                            </span>
                                                @endcan
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

    <script>
        feather.replace();
        $('#myTab a').on('click', function (e) {
            e.preventDefault()
            $(this).tab('show')
        });
    </script>

@endsection
