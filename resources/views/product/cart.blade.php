@extends('templates.main')

@section('content')
    <h1 class="display-2 text-center">Your Cart <img src="/image/carts.png"/></h1>

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
            <table class="table" style="margin-top: 20px">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Product</th>
                    <th scope="col"></th>
                    <th scope="col">Unit Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $i)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>
                            <img src="/storage/product/{{ $i->product_image_path }}" style="width:120px; height:120px;">
                        </td>
                        <td>{{ $i->product_name }}</td>
                        <td><img src="/image/malaysia.png"> {{ $i->product_price }}</td>
                        <td>
                            <input type='number' value="1" class="text-center">
                        </td>
                        <td>
                            <button type="button" class="btn btn-info" style="background-color: transparent; border: none">
                                X
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card" style="margin-top: 10px">
        <div class="card-body text-center">
            <p class="lead">
                Merchandise Subtotal ({{ $items->count() }} items):
            </p>
            <h1 class="display-5">RM {{ number_format($items->sum('product_price'), 2) }}</h1>
            <div>
                <img class="mr-2" width="60px"
                     src="https://bit.ly/326S7z6"
                     alt="Visa">
                <img class="mr-2" width="60px"
                     src="https://bit.ly/3fYjx2m"
                     alt="American Express">
                <img class="mr-2" width="60px"
                     src="https://bit.ly/3uJVxo3"
                     alt="Mastercard">
            </div>
            <button type="button" class="btn btn-primary btn-lg" style="margin-top: 20px">Check Out</button>
        </div>
    </div>

@endsection
