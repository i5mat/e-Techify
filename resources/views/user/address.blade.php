@extends('templates.main')

@section('content')
    <h1 class="display-2 text-center">Insert Address <img src="/image/locations.png"/></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>A well-known quote, contained in a blockquote element.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Someone famous in <cite title="Source Title">Source Title</cite>
        </figcaption>
    </figure>

    <div class="card" style="padding: 20px 40px;">
    <form method="POST" action="{{ route('user.userinsertaddress') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="user_name" name="user_name" placeholder="test">
            <label for="user_name">Name</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="user_phone" name="user_phone" placeholder="test">
            <label for="user_phone">Phone No.</label>
        </div>
        <div class="form-floating" style="margin-bottom: 10px">
            <textarea class="form-control" placeholder="Leave a comment here" id="user_address" name="user_address" style="height: 120px"></textarea>
            <label for="floatingTextarea2">Address</label>
        </div>
        <button type="submit" class="btn btn-primary float-end">Submit</button>
    </form>
    </div>

    <div class="card text-center" style="margin-top: 10px">
        <div class="card-body">
            <table class="table" style="margin-top: 20px">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Address</th>
                    <th scope="col">Created On</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($userinfo as $i)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>
                            <b>{{ $i->name }}</b>
                        </td>
                        <td>
                            (+60) {{ $i->phone_no }}
                        </td>
                        <td>
                            {{ $i->address }}
                        </td>
                        <td>
                            {{ date('Y-m-d H:i:s A', strtotime($i->created_at)) }}
                        </td>
                        <td>
                            <button type="button" class="btn btn-info" style="background-color: transparent; border: none"
                                    onclick="event.preventDefault();
                                        document.getElementById('delete-address-{{ $i->id }}').submit()">
                                <img src="/image/close.png">
                            </button>

                            <form id="delete-address-{{ $i->id }}" action="{{ route('user.userdel_address', $i->id) }}" method="POST" style="display: none">
                                @csrf
                                @method("DELETE")
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
