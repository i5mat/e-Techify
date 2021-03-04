@extends('templates.main')

@section('content')
    <h1>Verify E-Mail Address</h1>
    <p>You must verify your email address to access this page.</p>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Resend Verification Email</button>

    </form>
@endsection
