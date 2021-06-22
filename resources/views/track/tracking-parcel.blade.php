@extends('templates.main')

@section('content')
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <style>
        .search {
            position: relative
        }

        .search input {
            height: 60px;
            text-indent: 25px;
            border: 2px solid #d6d4d4
        }

        .search input:focus {
            box-shadow: none;
            border: 2px solid blue
        }

        .search .fa-search {
            position: absolute;
            top: 20px;
            left: 16px
        }

        .search button {
            position: absolute;
            top: 5px;
            right: 5px;
            height: 50px;
            width: 110px;
            background: blue
        }
    </style>

    <h1 class="display-2 text-center">Tracking Status <img src="/image/parcel.png"/></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>Tracking parcel status here.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Prepared by <cite title="Source Title">Wan Ismat</cite>
        </figcaption>
    </figure>

    @if(!empty($errorMsg))
        <div class="alert alert-danger"> {{ $errorMsg }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="container">
                <div class="row height d-flex justify-content-center align-items-center">
                    <div class="col-md-8">
                        <form method="POST" action="{{ route('track.parcel') }}" id="tracking_form">
                        @csrf
                            <div class="search">
                                <i class="fa fa-search"></i>
                                <input type="text" class="form-control" placeholder="Insert tracking num" name="search-track-num" id="search-track-num">
                                <button class="btn btn-primary" type="button" id="track_parcel_btn">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        feather.replace();
        $("#track_parcel_btn").click(function() {
            if ($("#search-track-num").val() === '') {
                Swal.fire(
                    'Input NULL',
                    'Please input tracking field',
                    'error'
                )
            } else
                $("#tracking_form").submit();
        });

    </script>
@endsection
