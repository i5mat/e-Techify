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

    <div class="card">
        <div class="card-body" >
            @foreach($user as $usr)
            <div class="card mb-3" style="width: 100%;">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="/image/suncycle.png" alt="..." style="width: 90%; margin-top: 25px; margin-left: 25px">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">{{ $usr->name }}</h5>
                            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                            <p class="card-text"><small class="text-muted">Last updated 3 mins ago, {{ $usr->role_name }}</small>
                                <a href="{{ route('distributor.specific.stock.view', $usr->id) }}">
                                    <button class="btn btn-primary float-end" style="margin-bottom: 15px" type="button">
                                        Go {{ $usr->id }}
                                    </button>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection
