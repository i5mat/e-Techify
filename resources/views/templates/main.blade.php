<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="shortcut icon" type="image/x-icon" href="/image/online-sale.png" />
        <title>e-Techify</title>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        {{-- HighCharts --}}
        {{-- <script src="https://code.highcharts.com/highcharts.js"></script> --}}
        <script src="https://code.highcharts.com/maps/highmaps.js"></script>
        <script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/mapdata/countries/my/my-all.js"></script>
        <script src="https://code.highcharts.com/highcharts-more.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>

        {{-- JQuery CDN --}}
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://unpkg.com/feather-icons"></script>
        <script src="{{ asset('js/app.js') }}" defer></script>

        {{-- DataTables --}}
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>

        {{-- Polyfill --}}
        {{--<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>--}}

        {{-- Tippy.JS --}}
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script src="https://unpkg.com/tippy.js@6"></script>

        <style>
            #graph {
                height: 400px;
            }

            .highcharts-figure, .highcharts-data-table table {
                min-width: 310px;
                max-width: 800px;
                margin: 1em auto;
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
                <a class="navbar-brand display-6" href="#"><img src="/image/online-sale.png">&nbsp; e-Techify</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                    <div class="form-inline my-2 my-lg-0">
                        @if (Route::has('login'))
                            <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                                @auth
                                    @can('is-user')<a href="{{ route('product.manageCart') }}" style="padding-right: 10px;"><i class="fa fa-shopping-cart fa-2x"></i></a>@endcan
                                    <a href="{{ url('/user/profile') }}" style="padding-right: 10px;"><i class="fa fa-user fa-2x"></i></a>
                                    <a href="{{ url('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();"><i class="fa fa-chevron-circle-right fa-2x"></i></a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none">
                                        @csrf
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" style="padding-right: 10px;" class="text-sm text-gray-700 underline"><i class="fa fa-sign-in-alt fa-2x"></i></a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline"><i class="fa fa-plus-circle fa-2x"></i></a>
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
                            <a class="nav-link font-monospace" href="{{ route('user.userdash') }}">Home</a>
                        </li>
                        @can('is-reseller')
                            <li class="nav-item">
                                <a class="nav-link font-monospace" href="{{ route('admin.users.index') }}">Users</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle font-monospace" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Products
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="font-monospace dropdown-item" href="{{ route('product.insertProd') }}">Insert</a></li>
                                    <li><a class="font-monospace dropdown-item" href="{{ route('product.manageProd') }}">Update.Delete</a></li>
                                </ul>
                            </li>
                        @endcan
                        @can('is-user')
                            <li class="nav-item">
                                <a class="nav-link font-monospace" href="{{ route('product.items.index') }}">
                                    Shop
                                </a>
                            </li>
                        @endcan
                        @can('is-user-reseller')
                            <li class="nav-item">
                                <a class="nav-link font-monospace" href="{{ route('order.orders.index') }}">Manage Orders</a>
                            </li>
                        @endcan
                        @can('is-distributor')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle font-monospace" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Products
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="font-monospace dropdown-item" href="{{ route('product.insertProd') }}">Insert</a></li>
                                    <li><a class="font-monospace dropdown-item" href="{{ route('product.manageProd') }}">Update.Delete</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link font-monospace" href="{{ route('job.new') }}">e-Job</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link font-monospace" href="{{ route('shipment.new') }}">Shipment Requested</a>
                            </li>
                        @endcan
                        @can('is-user')
                            <li class="nav-item">
                                <a class="nav-link font-monospace" href="{{ route('rma.new.request') }}">e-RMA</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link font-monospace" href="{{ route('track.new.parcel') }}">e-Track Parcel</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link font-monospace" href="{{ route('job.new') }}">e-Job</a>
                            </li>
                        @endcan
                        @can('is-reseller')
                            <li class="nav-item">
                                <a class="nav-link font-monospace" href="{{ route('job.new') }}">e-Job</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link font-monospace" href="{{ route('distributor.stock.management.index') }}">Stock Management</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link font-monospace" href="{{ route('shipment.new') }}">Request Shipment</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link font-monospace" href="{{ route('order.find-receipt') }}">Receipt Finder</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link font-monospace" href="{{ route('distributor.insert.product.dist') }}">Insert SN</a>
                            </li>
                        @endcan
                        <li class="nav-item">
                            <a class="nav-link font-monospace" href="{{ route('covid.index') }}">nCov-19</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        @endcan

        <main class="container">
            @include('partials.alerts')
            @yield('content')
        </main>

        <div class="footer">
            @guest
                <div class="container py-4">
                    <div class="row align-items-md-stretch">
                        <div class="col-md-4 mt-3 mb-3">
                            <h1 class="display-6">Xmiryna Technology [SA0546866-M]</h1>
                            No. 79 Jalan Taman Melati 1,<br>
                            Taman Melati, Setapak,<br>
                            53100, Kuala Lumpur<br>
                            xmiryna.tech@outlook.com <br>
                            <b>+(60) 17-217 8319</b> / <strong>xmiryna.com.my</strong>
                        </div>
                        <div class="col-md-4 mt-3 mb-3">
                            <h1 class="display-6">Secure Payment</h1>
                            <i class="fab fa-cc-visa fa-3x"></i>
                            <i class="fab fa-cc-mastercard fa-3x mx-2"></i>
                            <i class="fab fa-cc-paypal fa-3x"></i><br>
                            <p class="mt-2">
                                Make payments here with secure, without worry.
                            </p>
                        </div>
                        <div class="col-md-4 mt-3 mb-3">
                            <h1 class="display-6">Service Available</h1>
                            <p>
                                <i class="fa fa-arrow-right"></i> Custom PC Build
                            </p>
                            <p>
                                <i class="fa fa-arrow-right"></i> PC Parts
                            </p>
                            <p>
                                <i class="fa fa-arrow-right"></i> Laptops
                            </p>
                            <p>
                                <i class="fa fa-arrow-right"></i> Cleaning Service
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <p style="padding-top: 30px; padding-bottom: 30px;"><img src="/image/online-sale.png"></p>
            @endguest
        </div>

        <script>
            console.log('%cStop!', 'color: red; font-size: 30px; font-weight: bold;');

            $('#exampleModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget) // Button that triggered the modal
                var prod_name = button.data('myprodname') // Extract info from data-* attributes
                var prod_sn = button.data('myprodsn') // Extract info from data-* attributes
                var prod_id = button.data('myprodid') // Extract info from data-* attributes
                var prod_pic = button.data('myprodpic') // Extract info from data-* attributes
                var prod_price = button.data('myprodprice') // Extract info from data-* attributes
                var prod_stock = button.data('myprodstock') // Extract info from data-* attributes
                var new_prod_price = button.data('myprodnewprice') // Extract info from data-* attributes
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
                modal.find('.modal-body #prod_stock').val(prod_stock);
                modal.find('.modal-body #new_prod_price').val(new_prod_price);
                //modal.find('.modal-body #enddate').val(end);
                //modal.find('.modal-body #event_types').val(event_type);
                //modal.find('.modal-body #event_levels').val(event_level);
                //document.cookie = 'name='.concat(event_type);

                var name = '{{ env('APP_URL') }}';
                document.getElementById("myForm").action = name+"/product/items/" + prod_id;
                document.getElementById("myImg").src = "{{ \Storage::disk('s3')->url('product/') }}" + prod_pic;
            });
        </script>

    </body>
</html>
