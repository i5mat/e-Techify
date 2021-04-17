@extends('templates.main')

@section('content')

    <div class="jumbotron text-center">
        <h1 class="display-3">Thank You!</h1>
        <p class="lead"><strong>Please check your email</strong> for order confirmation.</p>
        <hr>
        <p>
            Having trouble? <a href="#">Contact us</a>
        </p>
        <p class="lead">
            <a class="btn btn-primary btn-sm" href="{{ url('/order/orders') }}" role="button">Continue to homepage</a>
        </p>
    </div>

@endsection
