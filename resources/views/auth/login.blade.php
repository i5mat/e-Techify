@extends('templates.main')

@section('content')

    <div class="container mt-5">
        <div class="row">
            <div class="col g-0">
                <img class="h-100 w-100" src="/image/login-ui.png">
            </div>
            <div class="col mt-5">
                <div class="card mx-auto" style="padding: 20px 30px;">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input name="email" type="email" class="form-control rounded-pill @error('email') is-invalid @enderror" id="email" aria-describedby="email" value="{{ old('email') }}">
                            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                            @error('email')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input name="password" type="password" class="form-control rounded-pill @error('password') is-invalid @enderror" id="password" value="{{ old('password') }}">
                            @error('password')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <i class="fa fa-asterisk"></i> Forgotten your password? <a href="{{ route('password.request') }}">Reset it here.</a>
                        <button type="submit" class="btn btn-primary rounded-pill" style="width: 100%; margin-top: 10px">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
