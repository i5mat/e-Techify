<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>e-Techify</title>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        {{-- HighCharts --}}
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/highcharts-more.js"></script>
        <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>

        {{-- JQuery CDN --}}
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('js/app.js') }}" defer></script>

        <style>
            #applewatch, #applewatch2 {
                max-width: 380px;
                margin:0px auto;
            }

            .highcharts-figure, .highcharts-data-table table {
                min-width: 380px;
                max-width: 600px;
                margin: 0 auto;
            }

            .highcharts-data-table table {
                font-family: Verdana, sans-serif;
                border-collapse: collapse;
                border: 1px solid #EBEBEB;
                margin: 10px auto;
                text-align: center;
                width: 100%;
                max-width: 500px;
            }
            .highcharts-data-table caption {
                padding: 1em 0;
                font-size: 1.2em;
                color: #555;
            }
            .highcharts-data-table th {
                font-weight: 600;
                padding: 0.5em;
            }
            .highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
                padding: 0.5em;
            }
            .highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
                background: #f8f8f8;
            }
            .highcharts-data-table tr:hover {
                background: #f1f7ff;
            }
        </style>

    </head>
    <body>
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="#"><img src="/image/online-sale.png"> e-Techify</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                    <div class="form-inline my-2 my-lg-0">
                        @if (Route::has('login'))
                            <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                                @auth
                                    <a href="{{ url('/user/profile') }}" style="padding-right: 10px;"><span class="badge">10</span><img src="/image/shopping-cart.png"></a>
                                    <a href="{{ url('/user/profile') }}" style="padding-right: 10px;"><img src="/image/profile-user.png"></a>
                                    <a href="{{ url('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();"><img src="/image/logout.png"></a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none">
                                        @csrf
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" style="padding-right: 10px;" class="text-sm text-gray-700 underline"><img src="/image/login.png"></a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline"><img src="/image/register.png"></a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
            </div>
        </nav>

        @can('logged-in')
        <nav class="navbar sub-nav navbar-expand-lg">
            <div class="container">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.userdash') }}">Home</a>
                        </li>
                        @can('is-reseller')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.users.index') }}">Users</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('product.items.index') }}">Shop</a>
                            </li>
                            <li class="nav-item">
                                <div class="nav-link dropdown">
                                    <button class="dropbtn" style="background-color: #FFF">Manage Product</button>
                                    <div class="dropdown-content">
                                        <a href="{{ route('product.insertProd') }}">Insert Product</a>
                                        <a href="#">Link 2</a>
                                        <a href="#">Link 3</a>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item">

                            </li>
                        @endcan
                    </ul>
                </div>
            </div>
        </nav>
        @endcan

        <main class="container">
            @include('partials.alerts')
            @yield('content')
        </main>

        <br>
        <div class="footer">
            <p style="padding-top: 30px; padding-bottom: 30px;"><img src="/image/online-sale.png"></p>
        </div>

        <script>
            $('#exampleModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget) // Button that triggered the modal
                var prod_name = button.data('myprodname') // Extract info from data-* attributes
                var prod_sn = button.data('myprodsn') // Extract info from data-* attributes
                var prod_id = button.data('myprodid') // Extract info from data-* attributes
                var prod_pic = button.data('myprodpic') // Extract info from data-* attributes
                var prod_price = button.data('myprodprice') // Extract info from data-* attributes
                //var start = button.data('mystart') // Extract info from data-* attributes
                //var end = button.data('myend') // Extract info from data-* attributes
                //var event_type = button.data('myeventtype') // Extract info from data-* attributes
                //var event_level = button.data('myeventlevel') // Extract info from data-* attributes
                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                var modal = $(this)
                modal.find('.modal-body #prod_name').val(prod_name);
                modal.find('.modal-body #prod_sn').val(prod_sn);
                modal.find('.modal-body #prod_id').val(prod_id);
                //modal.find('.modal-body #prod_pic').val(prod_pic);
                modal.find('.modal-body #prod_price').val(prod_price);
                //modal.find('.modal-body #startdate').val(start);
                //modal.find('.modal-body #enddate').val(end);
                //modal.find('.modal-body #event_types').val(event_type);
                //modal.find('.modal-body #event_levels').val(event_level);
                //document.cookie = 'name='.concat(event_type);

                document.getElementById("myForm").action = "http://127.0.0.1:8000/product/items/" + prod_id;
                document.getElementById("myImg").src = "/storage/product/" + prod_pic;
            });
        </script>

    </body>
</html>
