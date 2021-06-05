@extends('templates.main')

@section('content')

    @can('logged-in')

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
                            <span>
                                <a
                                    href="#"
                                    data-myprodid="{{ $p->id }}"
                                    data-myshopprodname="{{ $p->product_name }}"
                                    data-myprodsn="{{ $p->product_sn }}"
                                    data-myprodpic="{{ $p->product_image_path }}"
                                    data-myprodprice="{{ $p->product_price }}"
                                    data-myprodstock="{{ $p->product_stock_count }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop"><i data-feather="info" class="float-end feather-24" style="margin-right: 10px"></i></a></span>
                        </p>
                        <small class="text-muted">Last updated {{ date('d/m/Y H:i A', strtotime($p->created_at ))}}</small>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
        {{ $products->appends(request()->input())->links() }}
    @endcan

    @guest
        <h1>Hi Homepage</h1>

        <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
            <div class="card-header">Header</div>
            <div class="card-body">
                <h5 class="card-title">Primary card title</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            </div>
        </div>
        <div class="card text-white bg-info mb-3" style="max-width: 18rem;">
            <div class="card-header">Header</div>
            <div class="card-body">
                <h5 class="card-title">Info card title</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            </div>
        </div>
    @endguest

    <script>
        feather.replace();

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
