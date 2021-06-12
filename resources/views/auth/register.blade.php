@extends('templates.main')

@section('content')
    <h1 class="text-center mt-5">
        <i class="fa fa-user-plus fa-2x"></i>
    </h1>
    <div class="card mx-auto" style="padding: 20px 30px; width: 50%">
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input name="name" type="text" class="form-control rounded-pill @error('name') is-invalid @enderror" id="name" aria-describedby="name" value="{{ old('name') }}">
            @error('name')
                <span class="invalid-feedback" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input name="email" type="email" class="form-control rounded-pill @error('email') is-invalid @enderror" id="email" aria-describedby="email" value="{{ old('email') }}">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            @error('email')
            <span class="invalid-feedback" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input name="password" type="password" class="form-control rounded-pill @error('password') is-invalid @enderror" id="password" value="{{ old('password') }}">
            @error('password')
            <span class="invalid-feedback" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input name="password_confirmation" type="password" class="form-control rounded-pill" id="password_confirmation">
            <div id="confirmPassHelp" class="form-text">Please make sure password and confirm password are tele.</div>
        </div>
        <button type="submit" class="btn btn-primary rounded-pill" style="width: 100%">Submit</button>
    </form>
    </div>
@endsection
