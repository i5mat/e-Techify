<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>e-Techify</title>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <script src="{{ asset('js/app.js') }}" defer></script>

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
                            <a class="nav-link" href="/">Home</a>
                        </li>
                        @can('is-reseller')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.users.index') }}">Users</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('product.items.index') }}">Shop</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('product.insertProd') }}">Insert Product</a>
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

    </body>
</html>
