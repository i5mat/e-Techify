@extends('templates.main')

@section('content')
    <h1 class="text-center display-2">Edit User</h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>Edit current user info.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Prepared by <cite title="Source Title">Wan Ismat</cite>
        </figcaption>
    </figure>

    <div class="container">
        <div class="card mx-auto" style="padding: 10px 10px; width: 50%;">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                            @method('PATCH')
                            @include('admin.users.partials.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
