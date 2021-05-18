@extends('templates.main')

@section('content')
    <h1 class="display-2 text-center">Your Orders! <img src="/image/received.png"/></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>A well-known quote, contained in a blockquote element.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Someone famous in <cite title="Source Title">Source Title</cite>
        </figcaption>
    </figure>

    <div class="card">
        <div class="card-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">To Ship</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Completed</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="messages-tab" data-toggle="tab" href="#messages" role="tab" aria-controls="messages" aria-selected="false">Cancelled</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="settings-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-selected="false">/</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row g-1">
                        @foreach($to_ship as $i)
                            <div class="col-md-6">
                                <div class="card p-2 border-5 border-bottom border-primary" style="border: none">
                                    <div class="text-right badge bg-primary"> <small class="lead" style="color: white">Order #{{ $i->id }}</small> </div>
                                    <div class="text-center mt-2 p-3"> <img src="/image/XT-logo.png" width="100" height="65" /> <span class="d-block font-weight-bold"><span class="badge bg-primary" style="color: white">{{ $i->order_status }}</span></span>
                                        <hr> <span>Xmiryna Tech</span>
                                        <div class="d-flex flex-row align-items-center justify-content-center"> <i class="fa fa-map-marker"></i> <small class="mx-1">Kuala Lumpur, TX</small> </div>
                                        <div class="d-flex justify-content-between mt-3">
                                            <span>
                                                <a href="{{ route('track.index.trackparcel', $i->id) }}"><i data-feather="truck"></i></a>
                                            </span>
                                            <span>
                                                <a href="{{ route('order.index.orderdetails', $i->id) }}"><i data-feather="eye"></i></a>
                                            </span>
                                            <span>
                                                <a href="{{ route('order.purchase.receipt', $i->id) }}" target="_blank"><i data-feather="file-text"></i></a>
                                            </span>
                                            <span>
                                                <a href="{{ route('order.purchase.insertsn', $i->id) }}"><i data-feather="upload"></i></a>
                                            </span>

                                            <form id="get-awb-order-{{ $i->id }}" action="{{ route('order.purchase.awb', $i->id) }}" method="POST" style="display: none">
                                                @csrf
                                            </form>
                                            <button style="border: none" class="btn btn-sm btn-outline-dark" onclick="event.preventDefault(); document.getElementById('get-awb-order-{{ $i->id }}').submit()"><i data-feather="printer"></i></button>

                                            <form id="cancel-order-{{ $i->id }}" action="{{ route('order.order.cancel', $i->id) }}" method="POST" style="display: none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button @if(App\Models\Tracking::where('order_id', '=', $i->id)->where('current_status', '=', 'Processing Order')->exists()) disabled @endif style="border: none" class="btn btn-sm btn-outline-danger" onclick="event.preventDefault(); document.getElementById('cancel-order-{{ $i->id }}').submit()"><i data-feather="x-circle"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="row g-1">
                        @foreach($delivered as $i)
                            <div @if($delivered->count() == 1) class="col-md-12" @else class="col-md-6" @endif>
                                <div class="card p-2 border-5 border-bottom border-success" style="border: none">
                                    <div class="text-right badge bg-success"> <small class="lead" style="color: white">Order #{{ $i->id }}</small> </div>
                                    <div class="text-center mt-2 p-3"> <img src="/image/XT-logo.png" width="100" height="65" /> <span class="d-block font-weight-bold"><span class="badge bg-success" style="color: white">{{ $i->order_status }}</span></span>
                                        <hr> <span>Xmiryna Tech</span>
                                        <div class="d-flex flex-row align-items-center justify-content-center"> <i class="fa fa-map-marker"></i> <small class="mx-1">Kuala Lumpur, TX</small> </div>
                                        <div class="d-flex justify-content-between mt-3">
                                            <span>
                                                <a href="{{ route('track.index.trackparcel', $i->id) }}"><i data-feather="truck"></i></a>
                                            </span>
                                            <span>
                                                <a href="{{ route('order.index.orderdetails', $i->id) }}"><i data-feather="eye"></i></a>
                                            </span>
                                            <span>
                                                <a href="{{ route('order.purchase.receipt', $i->id) }}" target="_blank"><i data-feather="file-text"></i></a>
                                            </span>
                                            <span>
                                                <a href="{{ route('order.purchase.insertsn', $i->id) }}"><i data-feather="upload"></i></a>
                                            </span>

                                            <form id="get-awb-order-{{ $i->id }}" action="{{ route('order.purchase.awb', $i->id) }}" method="POST" style="display: none">
                                                @csrf
                                            </form>
                                            <button style="border: none" class="btn btn-sm btn-outline-dark" onclick="event.preventDefault(); document.getElementById('get-awb-order-{{ $i->id }}').submit()"><i data-feather="printer"></i></button>

                                            <form id="cancel-order-{{ $i->id }}" action="{{ route('order.order.cancel', $i->id) }}" method="POST" style="display: none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button @if(App\Models\Tracking::where('order_id', '=', $i->id)->where('current_status', '=', 'Processing Order')->exists()) disabled @endif style="border: none" class="btn btn-sm btn-outline-danger" onclick="event.preventDefault(); document.getElementById('cancel-order-{{ $i->id }}').submit()"><i data-feather="x-circle"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane fade" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                    <div class="row g-1">
                        @foreach($cancelled as $i)
                            <div class="col-md-6">
                                <div class="card p-2 border-5 border-bottom border-danger" style="border: none">
                                    <div class="text-right badge bg-danger"> <small class="lead" style="color: white">Order #{{ $i->id }}</small> </div>
                                    <div class="text-center mt-2 p-3"> <img src="/image/XT-logo.png" width="100" height="65" /> <span class="d-block font-weight-bold"><span class="badge bg-danger" style="color: white">{{ $i->order_status }}</span></span>
                                        <hr> <span>Xmiryna Tech</span>
                                        <div class="d-flex flex-row align-items-center justify-content-center"> <i class="fa fa-map-marker"></i> <small class="mx-1">Kuala Lumpur, TX</small> </div>
                                        <div class="d-flex justify-content-between mt-3">
                                            <span>
                                                <a href="{{ route('track.index.trackparcel', $i->id) }}"><i data-feather="truck"></i></a>
                                            </span>
                                            <span>
                                                <a href="{{ route('order.index.orderdetails', $i->id) }}"><i data-feather="eye"></i></a>
                                            </span>
                                            <span>
                                                <a href="{{ route('order.purchase.receipt', $i->id) }}" target="_blank"><i data-feather="file-text"></i></a>
                                            </span>
                                            <span>
                                                <a href="{{ route('order.purchase.insertsn', $i->id) }}"><i data-feather="upload"></i></a>
                                            </span>

                                            <form id="get-awb-order-{{ $i->id }}" action="{{ route('order.purchase.awb', $i->id) }}" method="POST" style="display: none">
                                                @csrf
                                            </form>
                                            <button style="border: none" class="btn btn-sm btn-outline-dark" onclick="event.preventDefault(); document.getElementById('get-awb-order-{{ $i->id }}').submit()"><i data-feather="printer"></i></button>

                                            <form id="cancel-order-{{ $i->id }}" action="{{ route('order.order.cancel', $i->id) }}" method="POST" style="display: none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button @if(App\Models\Tracking::where('order_id', '=', $i->id)->where('current_status', '=', 'Processing Order')->exists()) disabled @endif style="border: none" class="btn btn-sm btn-outline-danger" onclick="event.preventDefault(); document.getElementById('cancel-order-{{ $i->id }}').submit()"><i data-feather="x-circle"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">SIT AMET</div>
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
