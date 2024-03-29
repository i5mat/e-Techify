@extends('templates.main')

@section('content')

    <div class="row">
        <div class="col-12">
            <h1 class="display-2 text-center">Users</h1>

            <figure class="text-center">
                <blockquote class="blockquote">
                    <p>Manage all registered users.</p>
                </blockquote>
                <figcaption class="blockquote-footer">
                    Prepared by <cite title="Source Title">Wan Ismat</cite>
                </figcaption>
            </figure>

    {{-- <a class="btn btn-sm btn-success float-end" href="{{ route('admin.users.create') }}" role="button">Create</a> --}}
        </div>
    </div>

    <div class="card">
        <div class="card-body border-3 border-bottom border-warning">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <a class="btn btn-sm btn-primary" href="{{ route('admin.users.edit', $user->id) }}" role="button">Edit</a>

                            <button type="button" class="btn btn-sm btn-danger"
                                    onclick="event.preventDefault();
                                        document.getElementById('delete-user-form-{{ $user->id }}').submit();">
                                Delete
                            </button>

                            <form id="delete-user-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: none">
                                @csrf
                                @method("DELETE")
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $users->links() }}
        </div>
    </div>
@endsection
