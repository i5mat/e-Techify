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
{{ $products->appends(request()->input())->links() }}
