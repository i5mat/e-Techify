@extends('templates.main')

@section('content')
    <h1 class="display-2 text-center">Stock Management <img src="/image/packages.png"/></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>A well-known quote, contained in a blockquote element.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Someone famous in <cite title="Source Title">Source Title</cite>
        </figcaption>
    </figure>

    <div class="row">
        <div class="col-xl-12 col-lg-11">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0" style="font-weight: bold">List of Distributors</h6>
                </div>
                <div class="card-body border-3 border-bottom border-warning">
                    <div class="row g-1">
                        @foreach($user as $usr)
                            <div class="col-md-4">
                                <div class="card p-2" style="border: none">
                                    <div class="text-right badge bg-warning"> <small class="lead">{{ $usr->role_name }}</small> </div>
                                    <div class="text-center mt-2 p-3"> <img src="/image/XT-logo.png" width="100" height="65" /> <span class="d-block font-weight-bold">{{ $usr->name }}</span>
                                        <hr> <span>Xmiryna Tech</span>
                                        <div class="d-flex flex-row align-items-center justify-content-center"> <i class="fa fa-map-marker"></i> <small class="mx-1">Kuala Lumpur, TX</small> </div>
                                        <div class="d-flex justify-content-between mt-3">
                                            <span> <i data-feather="gitlab"></i> </span>
                                            <a href="{{ route('distributor.specific.stock.view', $usr->id) }}"><button class="btn btn-sm btn-outline-dark">Go</button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        feather.replace();
    </script>
@endsection
