@extends('templates.main')

@section('content')
    <h1 class="display-2 text-center">Your Order Details <img src="/image/box.png"/></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>A well-known quote, contained in a blockquote element.</p>
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
                <dd class="col-sm-9">{{ rand(0, 999999999999) }} <span class="badge bg-warning text-dark">J&T Express</span></dd>
            </dl>
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
                            <img src="/storage/product/{{ $i->product_image_path }}" style="width:120px; height:120px;">
                        </td>
                        <td>{{ $i->product_name }} <p class="lead">[x{{ $i->product_order_quantity }}]</p></td>
                        <td><img src="/image/malaysia.png"> <span>{{ $i->product_price }}</span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="text-center">
                <p class="lead">
                    Order total <p class="small">+ Free Shipping</p>
                </p>
                <h1 class="display-5">
                    RM {{ number_format($recipientInfo->payment_total / 1) }}.00
                </h1>
            </div>
        </div>
    </div>
@endsection
