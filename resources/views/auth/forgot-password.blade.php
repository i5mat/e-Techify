@extends('templates.main')

@section('content')
    <h1 class="text-center mt-5">
        Reset Password
    </h1>

    <div class="card mx-auto" style="padding: 20px 30px; width: 50%;">
        <form method="POST" action="{{ route('password.email') }}">
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
            <button type="submit" class="btn btn-primary rounded-pill" style="width: 100%">Reset Password</button>
        </form>
    </div>
@endsection
