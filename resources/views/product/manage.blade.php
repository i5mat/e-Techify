@extends('templates.main')

@section('content')

    <h1 class="display-2 text-center">Update.Delete Product <img src="/image/box.png"/></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>A well-known quote, contained in a blockquote element.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Someone famous in <cite title="Source Title">Source Title</cite>
        </figcaption>
    </figure>

        <!-- [START] Modal to edit product [START] -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="" id="myForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><img src="/image/link.png"> Product Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                            <input type="hidden" name="prod_id" id="prod_id" value="">
                            @csrf
                            {{ method_field('PUT') }}

                            <div class="col-md-12 text-center">
                                <img id="myImg" src="" width="200" height="200">
                            </div>

                            <label for="prod-name-label" class="col-md-4 col-form-label text-md-right">Product Name</label>

                            <div class="col-md-12">
                                <input id="prod_name" type="text" class="form-control" name="prod_name" value="" required autocomplete="prod_name" autofocus>
                            </div>

                            <label for="prod-sn-label" class="col-md-4 col-form-label text-md-right">Product SN</label>

                            <div class="col-md-12">
                                <input id="prod_sn" type="text" class="form-control" name="prod_sn" value="" required autocomplete="prod_sn" autofocus>
                            </div>

                            <label for="prod-sn-label" class="col-md-4 col-form-label text-md-right">Product Picture</label>

                            <div class="col-md-12">
                                <input type="file" name="prod_image_update" id="prod_image_update" class="form-control">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-md">
                                    <label for="prod-stock-label" class="col-md-4 col-form-label text-md-right">Stock</label>

                                    <div class="col-md-12">
                                        <input type="number" name="prod_stock" id="prod_stock" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md">
                                    <label for="prod-price-label" class="col-md-4 col-form-label text-md-right">Price</label>

                                    <div class="input-group mb-2">
                                        <span class="input-group-text" id="basic-addon1">RM</span>
                                        <input type="number" class="form-control" id="prod_price" name="prod_price">
                                    </div>
                                </div>
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <!-- [END] Modal to edit product [END] -->

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
                        <th scope="col">Actions</th>
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
                            <button type="button" class="btn btn-danger" style="background-color: transparent; border: none"
                                    onclick="event.preventDefault();
                                    document.getElementById('delete-product-{{ $prod->id }}').submit()">
                                <img src="/image/delete.png">
                            </button>

                            <form id="delete-product-{{ $prod->id }}" action="{{ route('product.items.destroy', $prod->id) }}" method="POST" style="display: none">
                                @csrf
                                @method("DELETE")
                            </form>

                            <button
                                type="button"
                                class="btn btn-success"
                                style="background-color: transparent; border: none"
                                data-bs-toggle="modal"
                                data-bs-target="#exampleModal"
                                data-myprodid="{{ $prod->id }}"
                                data-myprodname="{{ $prod->product_name }}"
                                data-myprodsn="{{ $prod->product_sn }}"
                                data-myprodpic="{{ $prod->product_image_path }}"
                                data-myprodprice="{{ $prod->product_price }}"
                                data-myprodstock="{{ $prod->product_stock_count }}">
                                <img src="/image/edit.png">
                            </button>

                            <a href="{{ $prod->product_link }}" target="_blank">
                                <button type="button" class="btn btn-info" style="background-color: transparent; border: none">
                                    <img src="/image/link.png">
                                </button>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- [END] Table to manage product [END] -->
        <br>
        {{ $products->links() }}

@endsection
