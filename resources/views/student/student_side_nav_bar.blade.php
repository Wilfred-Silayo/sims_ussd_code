<div class="container-fluid">
    <div class="row flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark">
            <div class="d-flex flex-column align-items-sm-start px-1 pt-2 text-white min-vh-100">
                <ul class="nav nav-pills nav-sidebar flex-column" id="menu">
                    <li class="nav-item">
                        <a href="#" class="nav-link px-0 ">
                            <span class="ms-1 d-none d-sm-inline text-white fw-bold fs-5">Main Campus</span></a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('student.dashboard')}}" class="nav-link d-block align-middle ps-2 pe-4 {{request()->routeIs('student.dashboard')? 'active': ''}}">
                            <i class="fa-solid text-white fa-tachometer-alt"></i>
                            <span class="ms-1 d-none d-sm-inline text-white">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('student.registration')}}" class="nav-link d-block align-middle ps-2 {{request()->routeIs('student.registration')? 'active': ''}}">
                            <i class="fa-solid text-white fa-user-plus"></i>
                            <span class="ms-1 d-none d-sm-inline text-white">Registration</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('student.course.registration')}}" class="nav-link align-middle ps-2 {{request()->routeIs('student.course.registration')? 'active': ''}}">
                            <i class="fa-solid text-white fa-book-open"></i>
                            <span class="ms-1 d-none d-sm-inline text-white">Course Registration</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('student.result')}}" class="nav-link align-middle ps-2 {{request()->routeIs('student.result')? 'active': ''}}">
                            <i class="fa-solid text-white fa-chart-line"></i>
                            <span class="ms-1 d-none d-sm-inline text-white">Results</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('student.fullProfile')}}"
                            class="nav-link ps-2 align-middle {{request()->routeIs('student.fullProfile')? 'active': ''}} ">
                            <i class="fa-solid text-white fa-user"></i>
                            <span class="ms-1 d-none d-sm-inline text-white">Profile</span> </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('student.changePassword')}}"
                            class="nav-link ps-2 align-middle {{request()->routeIs('student.changePassword')? 'active': ''}} ">
                            <i class="fa-solid text-white fa-lock"></i>
                            <span class="ms-1 d-none d-sm-inline text-white">Change Password</span> </a>
                    </li>

                </ul>
            </div>
        </div>
        <div class="col py-3">
            @yield('content')
        </div>
    </div>
</div>
