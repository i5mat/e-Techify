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
                    <a class="nav-link" id="messages-tab" data-toggle="tab" href="#messages" role="tab" aria-controls="messages" aria-selected="false">/</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="settings-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-selected="false">/</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    @foreach($to_ship as $i)
                    <div class="card" style="margin-top: 10px">
                        <div class="card-body">
                            <h5 class="card-title display-6">ORDER #{{ $loop->iteration }}</h5>
                            <p class="card-text small">{{ date('d-M-Y H:i A', strtotime($i->created_at)) }}</p><span class="badge bg-danger" style="color: white">{{ $i->order_status }}</span>
                            <a href="{{ route('order.index.orderdetails', $i->id) }}">
                                <button type="button" class="btn btn-dark float-end">View</button>
                            </a>

                            <button type="button" class="btn btn-warning float-end" style="margin-right: 10px" onclick="event.preventDefault();
                                document.getElementById('get-awb-order-{{ $i->id }}').submit()">
                                Print WayBill
                            </button>

                            <form id="get-awb-order-{{ $i->id }}" action="{{ route('order.purchase.awb', $i->id) }}" method="POST" style="display: none">
                                @csrf
                            </form>
                            <a href="{{ route('order.purchase.insertsn', $i->id) }}">
                                <button type="button" class="btn btn-primary float-end" style="margin-right: 10px">
                                    Insert SN
                                </button>
                            </a>
                            <a href="{{ route('order.purchase.receipt', $i->id) }}" target="_blank">
                                <button type="button" class="btn btn-success float-end" style="margin-right: 10px" >
                                    Get Receipt
                                </button>
                            </a>

                            <button type="button" class="btn btn-danger float-end" style="margin-right: 10px" onclick="event.preventDefault();
                                document.getElementById('cancel-order-{{ $i->id }}').submit()">
                                Cancel Order
                            </button>

                            <form id="cancel-order-{{ $i->id }}" action="{{ route('order.order.cancel', $i->id) }}" method="POST" style="display: none">
                                @csrf
                                @method('DELETE')
                            </form>

                            <a href="{{ route('track.index.trackparcel', $i->id) }}">
                                <button type="button" class="btn btn-primary float-end" style="margin-right: 10px">
                                    Update Tracking
                                </button>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    @foreach($delivered as $i)
                        <div class="card" style="margin-top: 10px">
                            <div class="card-body">
                                <h5 class="card-title display-6">ORDER #{{ $loop->iteration }}</h5>
                                <p class="card-text small">{{ date('d-M-Y H:i A', strtotime($i->created_at)) }}</p><span class="badge bg-success" style="color: white">{{ $i->order_status }}</span>
                                <a href="{{ route('order.index.orderdetails', $i->id) }}">
                                    <button type="button" class="btn btn-dark float-end">View</button>
                                </a>

                                <button type="button" class="btn btn-warning float-end" style="margin-right: 10px" onclick="event.preventDefault();
                                    document.getElementById('get-awb-order-{{ $i->id }}').submit()">
                                    Print WayBill
                                </button>

                                <form id="get-awb-order-{{ $i->id }}" action="{{ route('order.purchase.awb', $i->id) }}" method="POST" style="display: none">
                                    @csrf
                                </form>
                                <a href="{{ route('order.purchase.insertsn', $i->id) }}">
                                    <button type="button" class="btn btn-primary float-end" style="margin-right: 10px">
                                        Insert SN
                                    </button>
                                </a>
                                <a href="{{ route('order.purchase.receipt', $i->id) }}" target="_blank">
                                    <button type="button" class="btn btn-success float-end" style="margin-right: 10px" >
                                        Get Receipt
                                    </button>
                                </a>

                                <button type="button" class="btn btn-danger float-end" style="margin-right: 10px" onclick="event.preventDefault();
                                    document.getElementById('cancel-order-{{ $i->id }}').submit()">
                                    Cancel Order
                                </button>

                                <form id="cancel-order-{{ $i->id }}" action="{{ route('order.order.cancel', $i->id) }}" method="POST" style="display: none">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <a href="{{ route('track.index.trackparcel', $i->id) }}">
                                    <button type="button" class="btn btn-primary float-end" style="margin-right: 10px">
                                        Update Tracking
                                    </button>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="tab-pane fade" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                    LOL
                </div>
                <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">SIT AMET</div>
            </div>
        </div>
    </div>

    <script>
        $('#myTab a').on('click', function (e) {
            e.preventDefault()
            $(this).tab('show')
        });
    </script>

@endsection
