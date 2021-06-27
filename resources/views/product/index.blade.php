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
                                    <option value="asc">low to high</option>
                                    <option value="desc">high to low</option>
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

        <div class="row">
            @foreach($products as $p)
                <div class="card-group col-md-4" style="padding-bottom: 10px">
                    <div class="card">
                        <img class="card-img-top" src="/storage/product/{{ $p->product_image_path }}" style="width:300px; height:300px; display: block; margin-left: auto; margin-right: auto" alt="Card image cap">
                        <div class="card-body">
                            <h6>{{ $p->product_category }}</h6>
                            <h5 class="card-title">{{ $p->product_name }}</h5>
                            <p class="card-text">{{ $p->product_sn }}</p>
                            <p><span><img src="/image/security.png">&nbsp; {{ $p->product_warranty_duration }} Years Warranty </span></p>
                            {{-- <h5 class="text-decoration-line-through" style="color: red">RM {{ $p->new_product_price }}</h5> --}}
                            <h2>RM {{ $p->product_price }}
                                <button
                                    @if($p->product_stock_count == 0)
                                    disabled
                                    @endif
                                    type="button"
                                    class="btn btn-primary float-end"
                                    onclick="event.preventDefault();
                                        document.getElementById('add-to-cart-{{ $p->id }}').submit()">
                                    Add to cart
                                </button>
                            </h2>

                            <form id="add-to-cart-{{ $p->id }}" action="{{ route('product.addProdCart', $p->id) }}" method="POST" style="display: none">
                                @csrf
                            </form>

                        </div>
                        <div class="card-footer">
                            <p>
                            <span>
                                <img src="/image/boxes.png">&nbsp; {{ $p->product_stock_count }} @if($p->product_stock_count < 3) stock left @else piece available @endif
                            </span>
                                <span><a href="{{ $p->product_link }}" target="_blank"><img src="/image/link.png" class="float-end"></a></span>
                            </p>
                            <small class="text-muted">Last updated {{ date('d/m/Y H:i A', strtotime($p->created_at ))}}</small>
                        </div>
                    </div>
                </div>
            @endforeach
            {{ $products->appends(request()->input())->links() }}

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
                    window.location.href = "http://127.0.0.1:8000/product/items?product_brand=" + firstSelection;
                else if (secondSelection !== 'Please select...')
                    window.location.href = "http://127.0.0.1:8000/product/items?product_price=" + secondSelection;
                else if (firstSelection !== 'Please select...' && secondSelection !== 'Please select...')
                    window.location.href = "http://127.0.0.1:8000/product/items?product_price=" + secondSelection + "&product_brand=" + firstSelection;
            }
        });

        $( "#btn_reset_filter" ).click(function() {
            window.location.href = "http://127.0.0.1:8000/product/items";
        });
    </script>
@endsection
