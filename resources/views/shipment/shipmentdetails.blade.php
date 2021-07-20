@extends('templates.main')

@section('content')
    <h1 class="display-2 text-center">Requested Shipment <i class="fa fa-fire"></i></h1>

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

                <a href="{{ route('shipment.new') }}">
                    <button class="btn btn-primary mb-2 float-start" style="background-color: transparent; border: none; color: black"><i class="fa fa-angle-left fa-2x"></i></button>
                </a>

                <table class="table">
                    <thead>
                    <tr>
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
                            <td>
                                <img src="{{ \Storage::disk('s3')->url('product/'.$getShipment->product_image_path) }}"
                                     style="width:120px; height:120px;">
                            </td>
                            <td>{{ $getShipment->product_name }}</td>
                            <td hidden id="get_shipment_id">{{ $getShipment->shipment_id }}</td>
                            <td><img src="/image/malaysia.png"> <span>{{ $getShipment->new_product_price }}</span></td>
                            <td>x{{ $getShipment->product_order_quantity }}</td>
                            <td id="total_shipment_item{{ $loop->iteration }}">{{ $getShipment->new_product_price * $getShipment->product_order_quantity }}</td>
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

                @if(isset($resellerInfo->proof_of_payment))
                    <div class="mb-3">
                        <label class="form-label">Tracking No.</label>
                        <input type="text" class="form-control" id="inputTracking" aria-describedby="trackingHelp" value="{{ $resellerInfo->tracking_no }}">
                        <div id="trackingHelp" class="form-text">Insert tracking number here.</div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-success mt-2" style="width: 100%" id="btn_request_approval_shipment">
                                @if($resellerInfo->status == 'Approved' || $resellerInfo->status == 'Shipped')
                                    Update Tracking No.
                                @else
                                    Approve Request
                                @endif
                            </button>
                        </div>
                        <div class="col">
                            <a href="{{ \Storage::disk('s3')->url('shipments/'.$resellerInfo->proof_of_payment) }}" target="_blank">
                                <button class="btn btn-warning mt-2" style="width: 100%"><i class="fa fa-download"></i> Download Proof of Payment</button>
                            </a>
                        </div>
                    </div>
                @else
                    @if($resellerInfo->status == 'Approved')
                        <h1 class="display-6">Payment not completed, please ask for payment.</h1>
                    @elseif($resellerInfo->status == 'Waiting Approval')
                        <h1 class="display-6">Reseller waiting for your approval.</h1>
                        <button type="submit" class="btn btn-success mt-2" style="width: 100%" id="btn_request_approval_shipment">
                            @if($resellerInfo->status == 'Approved' || $resellerInfo->status == 'Shipped')
                                Update Tracking No.
                            @else
                                Approve Request
                            @endif
                        </button>
                    @endif
                @endif

            @endcan

            @can('is-reseller')
                    <a href="{{ route('shipment.new') }}">
                        <button class="btn btn-primary mb-2 float-start" style="background-color: transparent; border: none; color: black"><i class="fa fa-angle-left fa-2x"></i></button>
                    </a>

                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col"></th>
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
                                    <img src="{{ \Storage::disk('s3')->url('product/'.$getShipment->product_image_path) }}"
                                         style="width:120px; height:120px;">
                                </td>
                                <td>{{ $getShipment->product_name }}</td>
                                <td hidden id="get_shipment_id">{{ $getShipment->shipment_id }}</td>
                                <td><img src="/image/malaysia.png"> <span>{{ $getShipment->new_product_price }}</span></td>
                                <td>x{{ $getShipment->product_order_quantity }}</td>
                                <td id="total_shipment_item{{ $loop->iteration }}">{{ $getShipment->new_product_price * $getShipment->product_order_quantity }}</td>
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

                    @if(isset($resellerInfo->tracking_no) && $resellerInfo->status == 'Shipped')
                        <a class="btn btn-outline-dark" style="font-size: 15px"
                           onclick="linkTrack(this.innerText)">{{ $resellerInfo->tracking_no }}</a>
                    @elseif(!isset($resellerInfo->tracking_no) && $resellerInfo->status == 'Shipped')
                        <h1 class="display-6">Tracking not available, ask distributor for tracking no.</h1>
                    @endif

                @if($resellerInfo->status == 'Waiting Approval' || $resellerInfo->status =='Requested')
                    <h1 class="display-6">{{ $resellerInfo->remark }}</h1>
                @elseif($resellerInfo->status !='Shipped')
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
                        <button @if(App\Models\Shipment::where('id', '=', request()->route('id'))->where('status', '!=', 'Approved')->exists()) disabled @endif type="submit" class="btn btn-warning mt-2" style="width: 100%" id="btn_upload_payment">Upload</button>
                    </form>
                @endif
            @endcan
        </div>
    </div>

    <script src="//www.tracking.my/track-button.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        feather.replace()
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#btn_request_approval_shipment").click(function(e){

            if ($("#inputTracking").val() === '') {
                Swal.fire(
                    'Input NULL',
                    'Please input tracking number',
                    'error'
                )
            } else {
                e.preventDefault();

                var shipment_id = $("#get_shipment_id").text();
                var tracking_no = $("#inputTracking").val();
                var status = 'Approved';
                var update_remark = 'Approved, waiting for payment. Please upload receipt of payment below.';
                var name = '{{ env('APP_URL') }}';

                console.log(parseInt(shipment_id))
                console.log(status, update_remark, tracking_no)

                $.ajax({
                    type:'PATCH',
                    url:name+"/shipment/shipment-request-approval-distributor/" + parseInt(shipment_id),
                    data:{
                        id:parseInt(shipment_id),
                        status:status,
                        remark:update_remark,
                        tracking:tracking_no
                    },
                    success:function(data){
                        if ( data['success'] )
                            window.location.href = name+"/shipment/new-shipment";
                        else
                            alert('EXISTING.')

                    }
                });
            }
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
