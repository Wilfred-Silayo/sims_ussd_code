<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SIMS-USSD-CODE | @yield('title')</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
    <link rel="icon" href="{{asset('images/favicon.png')}}">
    <!-- Styles -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('font-awesome/css/fontawesome.css')}}">
    <link rel="stylesheet" href="{{asset('font-awesome/css/brands.css')}}">
    <link rel="stylesheet" href="{{asset('font-awesome/css/solid.css')}}">

</head>

<body>
    <div id="app">
        <!-- Top nav-bar-->
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: rgba(66, 102, 136, 0.7)">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"> <img src="{{asset('images/favicon.png')}}" alt="nit logo" height="50"
                        width="50"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <h3 class="text-white">STUDENT INFORMATION MANAGEMENT SYSTEM</h3>
                        </li>
                    </ul>
                    @if(auth()->guard('admin')->check())
                    <div class="nav-item dropdown d-flex align-items-center">
                        <img src="{{ asset('storage/'.Auth::guard('admin')->user()->profile_photo_path) }}"
                            alt="Profile Photo" width="50" height="50" style="object-fit: cover; border-radius: 50%;">
                        <div class="dropdown ms-2">
                            <a class="nav-link dropdown-toggle  bg-white" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::guard('admin')->user()->firstname }}&nbsp;{{ Auth::guard('admin')->user()->lastname }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('admin.logout') }}" onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </nav>
        <!-- Left nav-bar -->
        @if(auth()->guard('admin')->check())
        <!--student dashboard-->
        @include('admin.admin_side_bar')

        @else
        <main class="py-1">
            @yield('content')
        </main>
        @endif
        <footer class="footer fixed-bottom mt-4 py-3 mb-0 bg-body-tertiary ">
            <div class="container-fluid d-flex justify-content-end ">
                <span class="text-body-secondary">
                    © 2023 STUDENT MANAGEMENT SYSTEM [SIMS]</span>
            </div>
        </footer>

        <script src="{{asset('js/bootstrap.js')}}"></script>
    </div>
</body>

</html>