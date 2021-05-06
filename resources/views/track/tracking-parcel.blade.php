@extends('templates.main')

@section('content')
    <style>
        .tracking-detail {
            padding:3rem 0
        }
        #tracking {
            margin-bottom:1rem
        }
        [class*=tracking-status-] p {
            margin:0;
            font-size:1.1rem;
            color:#fff;
            text-transform:uppercase;
            text-align:center
        }
        [class*=tracking-status-] {
            padding:1.6rem 0
        }
        .tracking-status-intransit {
            background-color:#65aee0
        }
        .tracking-status-outfordelivery {
            background-color:#f5a551
        }
        .tracking-status-deliveryoffice {
            background-color:#f7dc6f
        }
        .tracking-status-delivered {
            background-color:#4cbb87
        }
        .tracking-status-attemptfail {
            background-color:#b789c7
        }
        .tracking-status-error,.tracking-status-exception {
            background-color:#d26759
        }
        .tracking-status-expired {
            background-color:#616e7d
        }
        .tracking-status-pending {
            background-color:#ccc
        }
        .tracking-status-inforeceived {
            background-color:#214977
        }
        .tracking-list {
            border:1px solid #e5e5e5
        }
        .tracking-item {
            border-left:1px solid #e5e5e5;
            position:relative;
            padding:2rem 1.5rem .5rem 2.5rem;
            font-size:.9rem;
            margin-left:3rem;
            min-height:5rem
        }
        .tracking-item:last-child {
            padding-bottom:4rem
        }
        .tracking-item .tracking-date {
            margin-bottom:.5rem
        }
        .tracking-item .tracking-date span {
            color:#888;
            font-size:85%;
            padding-left:.4rem
        }
        .tracking-item .tracking-content {
            padding:.5rem .8rem;
            background-color:#f4f4f4;
            border-radius:.5rem
        }
        .tracking-item .tracking-content span {
            display:block;
            color:#888;
            font-size:85%
        }
        .tracking-item .tracking-icon {
            line-height:2.6rem;
            position:absolute;
            left:-1.3rem;
            width:2.6rem;
            height:2.6rem;
            text-align:center;
            border-radius:50%;
            font-size:1.1rem;
            background-color:#fff;
            color:#fff
        }
        .tracking-item .tracking-icon.status-sponsored {
            background-color:#f68
        }
        .tracking-item .tracking-icon.status-delivered {
            background-color:#4cbb87
        }
        .tracking-item .tracking-icon.status-outfordelivery {
            background-color:#f5a551
        }
        .tracking-item .tracking-icon.status-deliveryoffice {
            background-color:#f7dc6f
        }
        .tracking-item .tracking-icon.status-attemptfail {
            background-color:#b789c7
        }
        .tracking-item .tracking-icon.status-exception {
            background-color:#d26759
        }
        .tracking-item .tracking-icon.status-inforeceived {
            background-color:#214977
        }
        .tracking-item .tracking-icon.status-intransit {
            color:#e5e5e5;
            border:1px solid #e5e5e5;
            font-size:.6rem
        }
        @media(min-width:992px) {
            .tracking-item {
                margin-left:10rem
            }
            .tracking-item .tracking-date {
                position:absolute;
                left:-10rem;
                width:7.5rem;
                text-align:right
            }
            .tracking-item .tracking-date span {
                display:block
            }
            .tracking-item .tracking-content {
                padding:0;
                background-color:transparent
            }
        }

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
            <p>Tracking status your parcel here!</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Someone famous in <cite title="Source Title">Source Title</cite>
        </figcaption>
    </figure>

    <div class="card">
        <div class="card-body">
            <div class="container">
                <div class="row height d-flex justify-content-center align-items-center">
                    <div class="col-md-8">
                        <form method="POST" action="{{ route('track.parcel') }}">
                        @csrf
                            <div class="search">
                                <i class="fa fa-search"></i>
                                <input type="text" class="form-control" placeholder="Insert tracking num" name="search-track-num" id="search-track-num">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        feather.replace();
    </script>
@endsection
