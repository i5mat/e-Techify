@extends('templates.main')

@section('content')

    <h1 class="display-2 text-center">Update & Delete <img src="/image/box.png"/></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>Manage products in shop.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Prepared by <cite title="Source Title">Wan Ismat</cite>
        </figcaption>
    </figure>

        <!-- [START] Modal to edit product [START] -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="" enctype="multipart/form-data" id="myForm">
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

                                    <div class="col">
                                        <input type="number" name="prod_stock" id="prod_stock" class="form-control">
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="prod-price-label" class="col-md-4 col-form-label text-md-right">Price</label>

                                    <div class="input-group mb-2">
                                        <span class="input-group-text" id="basic-addon1">RM</span>
                                        <input type="number" class="form-control" id="prod_price" name="prod_price">
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="prod-price-label" class="col col-form-label text-md-right">New Price</label>

                                    <div class="input-group mb-2">
                                        <span class="input-group-text" id="basic-addon1">RM</span>
                                        <input type="number" class="form-control" id="new_prod_price" name="new_prod_price">
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
            <div class="card-body" id="table_data_product">
                @if($products->count() <= 0)
                    <img src="/image/no-product.png" style="width: 400px; height: 400px; display: block; margin-left: auto; margin-right: auto">
                    <h1 class="display-6">All products inserted by you will be displayed here.</h1>
                @else
                    @include('manageProduct-pagination')
                @endif
            </div>
        </div>
        <!-- [END] Table to manage product [END] -->
        <br>

    <script type="application/javascript">
        $(document).ready(function() {

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetch_data(page);
            });

            function fetch_data(page) {
                var l = window.location;

                // the request path should be
                // domain.com/welcome/pagination
                console.log(l.origin+ ' ' +l.pathname)

                $.ajax({
                    url: l.origin + l.pathname + "/pagination?page=" + page,
                    success: function(products) {
                        $('#table_data_product').html(products);
                    }
                });
            }
        });
    </script>
@endsection
