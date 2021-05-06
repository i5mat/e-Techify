@extends('templates.main')

@section('content')

    @can('logged-in')

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
                        </p>
                        <small class="text-muted">Last updated {{ date('d/m/Y H:i A', strtotime($p->created_at ))}}</small>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
        {{ $products->links() }}
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
@endsection
