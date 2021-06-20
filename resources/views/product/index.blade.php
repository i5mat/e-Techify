@extends('templates.main')

@section('content')

    @can('logged-in')
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>

        <div class="container">
            <div class="row">
                <div class="col">
                    <h1 class="display-2 text-center">Shop <i class="fa fa-laptop"></i></h1>
                    <figure class="text-center">
                        <blockquote class="blockquote">
                            <p>Your shopping catalogue.</p>
                        </blockquote>
                        <figcaption class="blockquote-footer">
                            Prepared by <cite title="Source Title">Wan Ismat</cite>
                        </figcaption>
                    </figure>
                </div>
                <div class="col">
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-floating">
                                <select class="form-select" id="filterProductBrand" aria-label="Floating label select example" style="height: 60px">
                                    <option selected disabled>Please select...</option>
                                    @foreach($getProducts as $getProduct)
                                        <option value="{{ $getProduct->product_brand }}">{{ $getProduct->product_brand }}</option>
                                    @endforeach
                                </select>
                                <label for="filterProductBrand">Select Brand</label>
                            </div>
                            <button class="btn btn-warning mt-2 float-start" style="width: 100%" id="btn_apply_filter">Apply Filters</button>
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <select class="form-select" id="filterProductPrice" aria-label="Floating label select example" style="height: 60px">
                                    <option selected disabled>Please select...</option>
                                    <option value="asc">Ascending</option>
                                    <option value="desc">Descending</option>
                                </select>
                                <label for="filterProductPrice">Select Price</label>
                            </div>
                            <button class="btn btn-primary mt-2 float-start" style="width: 100%" id="btn_reset_filter">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row text-center">
                                <div class="col-md">
                                    <img id="myProdImg" src="" width="300" height="300">
                                </div>
                                <div class="col-md">
                                    <img id="importImg1" src="https://nzxt-site-media.s3-us-west-2.amazonaws.com/uploads/product_image/image/2947/large_3780fe7fcae57319.png" width="300" height="300">
                                </div>
                            </div>
                            <div class="row text-center">
                                <div class="col-xl">
                                    <h3 id="shop_prod_name"></h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl">
                                    <dl class="row">
                                        <dt class="col-sm-3">Description</dt>
                                        <dd class="col-sm-9">
                                            The all-new Kraken Z Series lets you personalize your all-in-one liquid cooler like never before. Through CAM’s unique software interface, you can do more than simply fine-tune settings; you can now display your favorite animated gifs or CAM system information, allowing for total customization. Backed by a 6-year warranty, the Kraken Z series provides superior performance in liquid cooling, simple installation, and a look that is uniquely your own.
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Understood</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="shop_table_data">
            @include('shop-pagination')
        </div>
    @endcan

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        feather.replace();

        $( "#btn_apply_filter" ).click(function() {
            var firstSelection = $( "#filterProductBrand :selected" ).val();
            var secondSelection = $( "#filterProductPrice :selected" ).val();

            if ($("#filterProductBrand :selected").text() === 'Please select...' && $("#filterProductPrice :selected").text() === 'Please select...') {
                Swal.fire(
                    'Input NULL',
                    'Please input brand and price',
                    'error'
                )
            } else {
                if (firstSelection !== 'Please select...')
                    if (secondSelection !== 'Please select...')
                        window.location.href = "http://127.0.0.1:8000/product/items?product_brand=" + firstSelection + "&product_price=" + secondSelection;
                    else if (secondSelection !== 'Please select...')
                        window.location.href = "http://127.0.0.1:8000/product/items?product_price=" + secondSelection;
                    else if (firstSelection !== 'Please select...')
                        window.location.href = "http://127.0.0.1:8000/product/items?product_brand=" + firstSelection;
            }
        });

        $( "#btn_reset_filter" ).click(function() {
            window.location.href = "http://127.0.0.1:8000/product/items";
        });

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
                        $('#shop_table_data').html(products);
                    }
                });
            }
        });

        $('#staticBackdrop').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var prod_name = button.data('myshopprodname') // Extract info from data-* attributes
            var prod_sn = button.data('myprodsn') // Extract info from data-* attributes
            var prod_id = button.data('myprodid') // Extract info from data-* attributes
            var prod_pic = button.data('myprodpic') // Extract info from data-* attributes
            var prod_price = button.data('myprodprice') // Extract info from data-* attributes
            var prod_stock = button.data('myprodstock') // Extract info from data-* attributes

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

            var modal = $(this)
            modal.find('.modal-body #shop_prod_name').val(prod_name);
            modal.find('.modal-body #prod_sn').val(prod_sn);
            modal.find('.modal-body #prod_id').val(prod_id);
            //modal.find('.modal-body #prod_pic').val(prod_pic);
            modal.find('.modal-body #prod_price').val(prod_price);
            modal.find('.modal-body #prod_stock').val(prod_stock);
            //document.cookie = 'name='.concat(event_type);

            document.getElementById("shop_prod_name").innerText = prod_name;
            document.getElementById("myProdImg").src = "/storage/product/" + prod_pic;
        });
    </script>
@endsection
