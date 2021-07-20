@extends('templates.main')

@section('content')
    <h1 class="display-2 text-center">Your Order Details <img src="/image/order-list.png"/></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>A well-known quote, contained in a blockquote element.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Someone famous in <cite title="Source Title">Source Title</cite>
        </figcaption>
    </figure>

    <div class="card" style="margin-bottom: 10px">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Name & Phone No.</dt>
                <dd class="col-sm-9" id="user_name"><b>{{ $recipientInfo->name }}
                        +(60) {{ $recipientInfo->phone_no }}</b></dd>

                <dt class="col-sm-3">Address</dt>
                <dd class="col-sm-9">
                    <p id="address">{{ $recipientInfo->address }}</p>

                <dt class="col-sm-3">Tracking No.</dt>
                <dd class="col-sm-9">{{ $recipientInfo->tracking_num }} <span class="badge bg-warning text-dark">XT Express</span>
                </dd>

                <dt class="col-sm-3">Order Created At</dt>
                <dd class="col-sm-9">{{ date('d-M-Y H:i A', strtotime($recipientInfo->created_at)) }}</dd>

                <dt class="col-sm-3">Invoice No.</dt>
                <dd class="col-sm-9">#{{ $recipientInfo->receipt_no }}</dd>
            </dl>

            <div class="steps d-flex flex-wrap flex-sm-nowrap justify-content-between padding-top-2x padding-bottom-1x">
                @if(\App\Models\Tracking::join('orders', 'orders.id', '=', 'trackings.order_id')->where('orders.id', '=', $recipientInfo->order_id)->where('current_status', '=', 'Confirmed Order')->exists())
                    <div class="step completed">
                        <div class="step-icon-wrap">
                            <div class="step-icon"><i data-feather="clipboard" class="feather-32" style="margin-bottom: 15px"></i></div>
                        </div>
                        <h4 class="step-title">Confirmed Order</h4>
                    </div>
                @else
                    <div class="step">
                        <div class="step-icon-wrap">
                            <div class="step-icon"><i data-feather="clipboard" class="feather-32" style="margin-bottom: 15px"></i></div>
                        </div>
                        <h4 class="step-title">Confirmed Order</h4>
                    </div>
                @endif
                @if(\App\Models\Tracking::join('orders', 'orders.id', '=', 'trackings.order_id')->where('orders.id', '=', $recipientInfo->order_id)->where('current_status', '=', 'Processing Order')->exists())
                    <div class="step completed">
                        <div class="step-icon-wrap">
                            <div class="step-icon"><i data-feather="chevrons-right" class="feather-32" style="margin-bottom: 15px"></i></div>
                        </div>
                        <h4 class="step-title">Processing Order</h4>
                    </div>
                @else
                    <div class="step">
                        <div class="step-icon-wrap">
                            <div class="step-icon"><i data-feather="chevrons-right" class="feather-32" style="margin-bottom: 15px"></i></div>
                        </div>
                        <h4 class="step-title">Processing Order</h4>
                    </div>
                @endif
                @if(\App\Models\Tracking::join('orders', 'orders.id', '=', 'trackings.order_id')->where('orders.id', '=', $recipientInfo->order_id)->where('current_status', '=', 'Quality Check')->exists())
                    <div class="step completed">
                        <div class="step-icon-wrap">
                            <div class="step-icon"><i data-feather="check-circle" class="feather-32" style="margin-bottom: 15px"></i></div>
                        </div>
                        <h4 class="step-title">Quality Check</h4>
                    </div>
                @else
                    <div class="step">
                        <div class="step-icon-wrap">
                            <div class="step-icon"><i data-feather="check-circle" class="feather-32" style="margin-bottom: 15px"></i></div>
                        </div>
                        <h4 class="step-title">Quality Check</h4>
                    </div>
                @endif
                @if(\App\Models\Tracking::join('orders', 'orders.id', '=', 'trackings.order_id')->where('orders.id', '=', $recipientInfo->order_id)->where('current_status', '=', 'Product Dispatched')->exists())
                    <div class="step completed">
                        <div class="step-icon-wrap">
                            <div class="step-icon"><i data-feather="truck" class="feather-32" style="margin-bottom: 15px"></i></div>
                        </div>
                        <h4 class="step-title">Product Dispatched</h4>
                    </div>
                @else
                    <div class="step">
                        <div class="step-icon-wrap">
                            <div class="step-icon"><i data-feather="truck" class="feather-32" style="margin-bottom: 15px"></i></div>
                        </div>
                        <h4 class="step-title">Product Dispatched</h4>
                    </div>
                @endif
                @if(\App\Models\Tracking::join('orders', 'orders.id', '=', 'trackings.order_id')->where('orders.id', '=', $recipientInfo->order_id)->where('current_status', '=', 'Product Delivered')->exists())
                    <div class="step completed">
                        <div class="step-icon-wrap">
                            <div class="step-icon"><i data-feather="check" class="feather-32" style="margin-bottom: 15px"></i></div>
                        </div>
                        <h4 class="step-title">Product Delivered</h4>
                    </div>
                @else
                    <div class="step">
                        <div class="step-icon-wrap">
                            <div class="step-icon"><i data-feather="check" class="feather-32" style="margin-bottom: 15px"></i></div>
                        </div>
                        <h4 class="step-title">Product Delivered</h4>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table text-center">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Product</th>
                    <th scope="col"></th>
                    <th scope="col">Unit Price</th>
                </tr>
                </thead>
                <tbody class="table">
                @foreach($orderInfo as $i)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>
                            <img src="{{ \Storage::disk('s3')->url('product/'.$i->product_image_path) }}" style="width:120px; height:120px;">
                        </td>
                        <td>{{ $i->product_name }} <p class="lead">[x{{ $i->product_order_quantity }}]</p></td>
                        <td><b>RM</b> <span>{{ $i->product_price }}</span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="text-center">
                <p class="lead">
                    Order total : {{ $recipientInfo->payment_method }} <p class="small">+ Included Shipping</p>
                </p>
                <h1 class="display-5">
                    RM {{ number_format($recipientInfo->payment_total / 1) }}.00
                </h1>
            </div>
        </div>
    </div>

    <script>
        feather.replace()
    </script>
@endsection
