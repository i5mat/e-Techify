@extends('templates.main')

@section('content')
    <h1 class="display-2 text-center">Requested Shipment Details <img src="/image/order-list.png"/></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>A well-known quote, contained in a blockquote element.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Someone famous in <cite title="Source Title">Source Title</cite>
        </figcaption>
    </figure>

    <div class="card text-center">
        <div class="card-body">
            @can('is-distributor')
                <div class="mb-3">
                    <label class="form-label">Tracking No.</label>
                    <input type="text" class="form-control" id="inputTracking" aria-describedby="trackingHelp" value="{{ $resellerInfo->tracking_no }}">
                    <div id="trackingHelp" class="form-text">Insert tracking number here.</div>
                </div>

                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col"></th>
                        <th scope="col">Name</th>
                        <th scope="col">Unit Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($getItems as $getShipment)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>
                                <img src="/storage/product/{{ $getShipment->product_image_path }}"
                                     style="width:120px; height:120px;">
                            </td>
                            <td>{{ $getShipment->product_name }}</td>
                            <td hidden id="get_shipment_id">{{ $getShipment->shipment_id }}</td>
                            <td><img src="/image/malaysia.png"> <span>{{ $getShipment->product_price }}</span></td>
                            <td>x{{ $getShipment->product_order_quantity }}</td>
                            <td id="total_shipment_item{{ $loop->iteration }}">{{ $getShipment->product_price * $getShipment->product_order_quantity }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="text-center">
                    <p class="lead">
                        Merchandise Subtotal ({{ $getItems->count() }} items):
                    </p>

                    <h1 class="display-5" id="total"></h1>
                </div>
                <button type="submit" class="btn btn-success mt-2" style="width: 100%" id="btn_request_approval_shipment">
                    @if(App\Models\Shipment::where('id', '=', request()->route('id'))->where('status', '=', 'Shipped')->exists())
                        Update Request
                    @else
                        Approve Request
                    @endif
                </button>
                <a href="{{ route('shipment.new') }}">
                    <button class="btn btn-primary mt-2" style="width: 100%">Back</button>
                </a>
            @endcan

            @can('is-reseller')
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col"></th>
                            <th scope="col">Name</th>
                            <th scope="col">Unit Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($getItems as $getShipment)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>
                                    <img src="/storage/product/{{ $getShipment->product_image_path }}"
                                         style="width:120px; height:120px;">
                                </td>
                                <td>{{ $getShipment->product_name }}</td>
                                <td hidden id="get_shipment_id">{{ $getShipment->shipment_id }}</td>
                                <td><img src="/image/malaysia.png"> <span>{{ $getShipment->product_price }}</span></td>
                                <td>x{{ $getShipment->product_order_quantity }}</td>
                                <td id="total_shipment_item{{ $loop->iteration }}">{{ $getShipment->product_price * $getShipment->product_order_quantity }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-center">
                        <p class="lead">
                            Merchandise Subtotal ({{ $getItems->count() }} items):
                        </p>

                        <h1 class="display-5" id="total"></h1>
                    </div>
                    @if(App\Models\Shipment::where('id', '=', request()->route('id'))->where('status', '=', 'Shipped')->exists())
                        <a class="btn btn-outline-dark" style="font-size: 15px"
                           onclick="linkTrack(this.innerText)">{{ $resellerInfo->tracking_no }}</a>
                        <a href="{{ route('shipment.new') }}">
                            <button class="btn btn-primary mt-2" style="width: 100%">Back</button>
                        </a>
                    @else
                        <form method="POST" action="{{ route('shipment.request', request()->route('id')) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <div class="form-floating mb-3">
                                <div class="col-sm-auto">
                                    <label class="form-label">Upload Receipt</label>
                                    <input accept="application/pdf" type="file" name="proof_of_payment_file" id="proof_of_payment_file" class="form-control">
                                    <h1 hidden class="display-5" id="shipment_id">{{ request()->route('id') }}</h1>
                                </div>
                            </div>
                            <button @if(App\Models\Shipment::where('id', '=', request()->route('id'))->where('status', '!=', 'Approved')->exists()) disabled @endif type="submit" class="btn btn-primary mt-2" style="width: 100%" id="btn_upload_payment">Upload</button>
                        </form>
                    @endif
            @endcan
        </div>
    </div>

    <script src="//www.tracking.my/track-button.js"></script>
    <script>
        feather.replace()
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#btn_request_approval_shipment").click(function(e){

            e.preventDefault();

            var shipment_id = $("#get_shipment_id").text();
            var tracking_no = $("#inputTracking").val();
            var status = 'Approved';
            var update_remark = 'Approved, waiting for payment. Please upload receipt of payment below.';

            console.log(parseInt(shipment_id))
            console.log(status, update_remark, tracking_no)

            $.ajax({
                type:'PATCH',
                url:"http://127.0.0.1:8000/shipment/shipment-request-approval-distributor/" + parseInt(shipment_id),
                data:{
                    id:parseInt(shipment_id),
                    status:status,
                    remark:update_remark,
                    tracking:tracking_no
                },
                success:function(data){
                    if ( data['success'] )
                        window.location.href = "http://127.0.0.1:8000/shipment/new-shipment";
                    else
                        alert('EXISTING.')

                }
            });
        });

        function linkTrack(num) {
            TrackButton.track({
                tracking_no: num
            });
        }

        var total_items = {{ $getItems->count() }};
        var merchandise_total = 0;

        for (i = 1; i <= total_items; i++) {
            var formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'MYR',
            });
            var total_price_item = $("#total_shipment_item"+ i).text();

            merchandise_total += parseInt(total_price_item);

            document.getElementById('total').innerHTML = formatter.format(merchandise_total);
            document.getElementById('total_shipment_item'+ i).innerHTML = formatter.format(total_price_item);
        }

    </script>
@endsection
