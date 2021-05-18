@extends('templates.main')

@section('content')

    <h1 class="display-2 text-center">Stock by {{ $user->name }} <img src="/image/box.png"/></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>A well-known quote, contained in a blockquote element.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Someone famous in <cite title="Source Title">Source Title</cite>
        </figcaption>
    </figure>

        <!-- [START] Table to manage product [START] -->
        <div class="card text-center">
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col">Name</th>
                        <th scope="col">Brand</th>
                        <th scope="col">Date</th>
                        <th scope="col">Stock</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $prod)
                    <tr>
                        <td><img src="/storage/product/{{ $prod->product_image_path }}" width="60" height="60"></td>
                        <th scope="row">{{ $prod->product_sn }}</th>
                        <td>{{ $prod->product_name }}</td>
                        <td>{{ $prod->product_brand }}</td>
                        <td>
                            {{ date('d/m/Y H:i A', strtotime($prod->created_at ))}}
                        </td>
                        <td>
                            {{ $prod->product_stock_count }}
                        </td>
                        <td>
                            @if ($prod->product_stock_count <= 2)
                                <span class="badge bg-danger" style="color: white">LOW STOCK</span>
                            @else
                                <span class="badge bg-success" style="color: white">READY STOCK</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                <a href="/distributor/stock-management">
                    <button class="btn btn-warning" style="width: 100%">Back</button>
                </a>
            </div>
        </div>
        <!-- [END] Table to manage product [END] -->
        <br>
        {{ $products->links() }}

@endsection
