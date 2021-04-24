@extends('templates.main')

@section('content')

    @can('logged-in')

{{--        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">--}}
{{--            <div class="carousel-indicators">--}}
{{--                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>--}}
{{--                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>--}}
{{--                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>--}}
{{--            </div>--}}
{{--            <div class="carousel-inner">--}}
{{--                <div class="carousel-item active">--}}
{{--                    <img src="/image/evga.jpg" style="width:300px; height:500px;" class="d-block w-100" alt="...">--}}
{{--                </div>--}}
{{--                <div class="carousel-item">--}}
{{--                    <img src="/image/nitroconcept.jpg" style="width:300px; height:500px;" class="d-block w-100" alt="...">--}}
{{--                </div>--}}
{{--                <div class="carousel-item">--}}
{{--                    <img src="/image/zowie.jpg" style="width:300px; height:500px;" class="d-block w-100" alt="...">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"  data-bs-slide="prev">--}}
{{--                <span class="carousel-control-prev-icon" aria-hidden="true"></span>--}}
{{--                <span class="visually-hidden">Previous</span>--}}
{{--            </button>--}}
{{--            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"  data-bs-slide="next">--}}
{{--                <span class="carousel-control-next-icon" aria-hidden="true"></span>--}}
{{--                <span class="visually-hidden">Next</span>--}}
{{--            </button>--}}
{{--        </div><br>--}}

{{--        <div class="card-group col-md-auto">--}}
{{--            <div style="background-color: #1E2632; width: 10rem">--}}
{{--                <img src="/image/fractal.png" class="card-img-top" alt="..."/>--}}
{{--            </div>--}}
{{--            <div style="background-color: #1E2632; width: 10rem">--}}
{{--                <img src="/image/ducky.png" class="card-img-top" alt="..."/>--}}
{{--            </div>--}}
{{--            <div  style="background-color: #1E2632; width: 10rem">--}}
{{--                <img src="/image/Glorious.png" class="card-img-top" alt="..."/>--}}
{{--            </div>--}}
{{--            <div  style="background-color: #1E2632; width: 10rem">--}}
{{--                <img src="/image/nzxt-logo.png" class="card-img-top" alt="..."/>--}}
{{--            </div>--}}
{{--            <div  style="background-color: #1E2632; width: 10rem">--}}
{{--                <img src="/image/Vive.png" class="card-img-top" alt="..."/>--}}
{{--            </div>--}}
{{--            <div  style="background-color: #1E2632; width: 10rem">--}}
{{--                <img src="/image/nitro-concepts.png" class="card-img-top" alt="..."/>--}}
{{--            </div>--}}
{{--            <div  style="background-color: #1E2632; width: 10rem">--}}
{{--                <img src="/image/thermal-grizzly.png" class="card-img-top" alt="..."/>--}}
{{--            </div>--}}
{{--            <div  style="background-color: #1E2632; width: 11rem">--}}
{{--                <img src="/image/zowie.png" class="card-img-top" alt="..."/>--}}
{{--            </div>--}}
{{--        </div><br>--}}

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
