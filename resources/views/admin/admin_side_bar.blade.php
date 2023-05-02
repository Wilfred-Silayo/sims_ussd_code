<div class="container-fluid">
    <div class="row flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark ">
            <div class="d-flex flex-column  align-items-sm-start px-1 pt-2 text-white min-vh-100">
                <ul class="nav nav-pills  nav-sidebar flex-column" id="menu">
                    <li class="nav-item">
                        <a href="#" class="nav-link px-0 ">
                            <span class="ms-1 d-none d-sm-inline text-white text-white fw-bold fs-5 ">Main Campus</span></a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('admin.dashboard')}}"
                            class="nav-link d-block  align-middle ps-2 pe-4 {{request()->routeIs('admin.dashboard')? 'active': ''}} ">
                            <i class="fa-solid fa-sharp fa-gauge text-white"></i>
                            <span class="ms-1 d-none d-sm-inline text-white">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('register.student')}}"
                            class="nav-link d-block  align-middle ps-2 {{request()->routeIs('register.student')? 'active': ''}} ">
                            <i class="fa-solid fa-sharp fa-user-plus text-white"></i>
                            <span class="ms-1 d-none d-sm-inline text-white">Register student</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('register.lecturer')}}"
                            class="nav-link d-block  align-middle ps-2 {{request()->routeIs('register.lecturer')? 'active': ''}} ">
                            <i class="fa-sharp fa-solid  text-white fa-user-plus"></i>
                            <span class="ms-1 d-none d-sm-inline text-white">Register Lecturer</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('register.department')}}"
                            class="nav-link align-middle ps-2 {{request()->routeIs('register.department')? 'active': ''}} ">
                            <i class="fa-solid text-white fa-school"></i>
                            <span class="ms-1 d-none d-sm-inline text-white">Register Department</span></a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('register.program')}}"
                            class="nav-link align-middle ps-2 {{request()->routeIs('register.program')? 'active': ''}} ">
                            <i class="fa-solid text-white fa-list"></i>
                            <span class="ms-1 d-none d-sm-inline text-white">Register Program </span></a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('register.module')}}"
                            class="nav-link align-middle ps-2 {{request()->routeIs('register.module')? 'active': ''}} ">
                            <i class="fa-solid text-white fa-plus"></i>                         
                            <span class="ms-1 d-none d-sm-inline text-white">Register Module </span></a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('student.results')}}"
                            class="nav-link align-middle ps-2 {{request()->routeIs('student.results')? 'active': ''}} ">
                            <i class="fa-solid text-white fa-book-open"></i>                         
                            <span class="ms-1 d-none d-sm-inline text-white">Student Results</span></a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('enrollment.view')}}"
                            class="nav-link align-middle ps-2 {{request()->routeIs('enrollment.view')? 'active': ''}} ">
                            <i class="fa-solid text-white fa-chart-line"></i>                       
                            <span class="ms-1 d-none d-sm-inline text-white">Student Enrollments</span></a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('admin.profile')}}"
                            class="nav-link ps-2 align-middle {{request()->routeIs('admin.profile')? 'active': ''}} ">
                            <i class="fa-solid text-white fa-user"></i>
                            <span class="ms-1 d-none d-sm-inline text-white">Profile</span> </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('admin.changePassword')}}"
                            class="nav-link ps-2 align-middle {{request()->routeIs('admin.changePassword')? 'active': ''}} ">
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