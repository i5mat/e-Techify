@extends('templates.main')

@section('content')

    <h1 class="text-center mt-5">
        <i class="fa fa-users fa-2x"></i>
    </h1>
    <div class="card mx-auto" style="padding: 20px 30px; width: 50%;">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="email" aria-describedby="email" value="{{ old('email') }}">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                @error('email')
                <span class="invalid-feedback" role="alert">
                        {{ $message }}
                </span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" id="password" value="{{ old('password') }}">
                @error('password')
                <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            Forgotten your password? <a href="{{ route('password.request') }}">Reset it here.</a>
            <button type="submit" class="btn btn-primary rounded-pill" style="width: 100%; margin-top: 10px">Login</button>
        </form>
    </div>

@endsection
