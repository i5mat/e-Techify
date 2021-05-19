@extends('templates.main')

@section('content')
    <h1 class="text-center">Update Profile</h1>

    <div class="card mx-auto" style="padding: 20px 30px; width: 50%;">
        <img src="/image/update-profile.jpg" class="card-img-top" alt="...">
        <form method="POST" action="{{ route('user-profile-information.update') }}">
            @csrf
            @method("PUT")
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" aria-describedby="name" value="{{ auth()->user()->name }}">
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="email" aria-describedby="email" value="{{ auth()->user()->email }}">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('user.useraddress') }}"><button type="button" class="btn btn-primary">Add New Address + </button></a>
        </form>
    </div>
@endsection
