@extends('templates.main')

@section('content')
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>

    <h1 class="display-2 text-center">Profile</h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>Manage your own profile info.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Prepared by <cite title="Source Title">Wan Ismat</cite>
        </figcaption>
    </figure>

    <div class="container mt-5">
        <div class="row">
            <div class="col g-0">
                <img class="h-100 w-100" src="/image/profile-ui.png">
            </div>
            <div class="col mt-5">
                <div class="card mx-auto" style="padding: 20px 30px;">
                    <form method="POST" action="{{ route('user-profile-information.update') }}" id="edit_profile_form">
                        @csrf
                        @method("PUT")
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input name="name" type="text" class="form-control rounded-pill @error('name') is-invalid @enderror"
                                   id="name" aria-describedby="name" value="{{ auth()->user()->name }}">
                            @error('name')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input name="email" type="email"
                                   class="form-control rounded-pill @error('email') is-invalid @enderror" id="email"
                                   aria-describedby="email" value="{{ auth()->user()->email }}">
                            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.
                            </div>
                            @error('email')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        @can('is-user-distributor')
                            <div class="row">
                                <div class="col">
                                    <button style="width: 100%" type="button" class="btn btn-primary" id="btn_edit_profile">Submit</button>
                                </div>
                                <div class="col">
                                    <a href="{{ route('user.useraddress') }}">
                                        <button style="width: 100%" type="button" class="btn btn-primary">Add New Address +</button>
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col">
                                    <button style="width: 100%" type="button" class="btn btn-primary" id="btn_edit_profile">Submit</button>
                                </div>
                            </div>
                        @endcan
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $("#btn_edit_profile").click(function() {
            if ($("#name").val() === '{{ auth()->user()->name }}' && $("#email").val() === '{{ auth()->user()->email }}') {
                Swal.fire(
                    'Same Input',
                    'Please make some changes to the input to make changes.',
                    'error'
                )
            } else
                $("#edit_profile_form").submit();
        });

    </script>
@endsection
