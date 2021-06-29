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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
        <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

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
                <a class="navbar-brand" href="#"><img src="/image/online-sale.png"> e-Techify</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                    <div class="form-inline my-2 my-lg-0">
                        @if (Route::has('login'))
                            <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                                @auth
                                    @can('is-user')<a href="{{ route('product.manageCart') }}" style="padding-right: 10px;"><span class="badge bg-danger font-monospace" style="color: white">Cart</span><i class="fa fa-shopping-cart fa-2x"></i></a>@endcan
                                    <a href="{{ url('/user/profile') }}" style="padding-right: 10px;"><i class="fa fa-user fa-2x"></i></a>
                                    <a href="{{ url('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();"><i class="fa fa-chevron-circle-right fa-2x"></i></a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none">
                                        @csrf
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" style="padding-right: 10px;" class="text-sm text-gray-700 underline"><i class="fa fa-sign-in fa-2x"></i></a>

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
                            <li class="nav-item">
                                <div class="nav-link dropdown">
                                    <button class="dropbtn font-monospace" style="background-color: #FFF">Manage Product</button>
                                    <div class="dropdown-content">
                                        <a class="font-monospace" href="{{ route('product.insertProd') }}">Insert</a>
                                        <a class="font-monospace" href="{{ route('product.manageProd') }}">Update.Delete</a>
                                    </div>
                                </div>
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
                            <li class="nav-item">
                                <div class="nav-link dropdown">
                                    <button class="dropbtn font-monospace" style="background-color: #FFF">Manage Product</button>
                                    <div class="dropdown-content">
                                        <a class="font-monospace" href="{{ route('product.insertProd') }}">Insert</a>
                                        <a class="font-monospace" href="{{ route('product.manageProd') }}">Update.Delete</a>
                                    </div>
                                </div>
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
                            <a class="nav-link font-monospace" href="{{ route('user.covid.index') }}">nCov-19</a>
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
            <p style="padding-top: 30px; padding-bottom: 30px;"><img src="/image/online-sale.png"></p>
        </div>

        <script>
            @can('is-user')
                //Start of Tawk.to Script

                var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
                (function(){
                    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
                    s1.async=true;
                    s1.src='https://embed.tawk.to/60befaa64ae6dd0abe7d09f4/1f7l0673b';
                    s1.charset='UTF-8';
                    s1.setAttribute('crossorigin','*');
                    s0.parentNode.insertBefore(s1,s0);
                })();

                //End of Tawk.to Script
            @endcan

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

                document.getElementById("myForm").action = "http://127.0.0.1:8000/product/items/" + prod_id;
                document.getElementById("myImg").src = "/storage/product/" + prod_pic;
            });
        </script>

    </body>
</html>
