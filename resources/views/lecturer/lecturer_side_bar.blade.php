<div class="container-fluid">
    <div class="row flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark">
            <div class="d-flex flex-column  align-items-sm-start px-1 pt-2 text-dark min-vh-100">
                <ul class="nav nav-pills  nav-sidebar flex-column" id="menu">
                    <li class="nav-item">
                        <a href="#" class="nav-link px-0 ">
                            <span class="ms-1 d-none d-sm-inline text-white fw-bold fs-5 ">Main Campus</span></a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('lecturer.dashboard')}}"
                            class="nav-link d-block  align-middle ps-2 pe-4 {{request()->routeIs('lecturer.dashboard')? 'active': ''}} ">
                            <i class="fa-solid fa-sharp fa-gauge text-white"></i>
                            <span class="ms-1 d-none d-sm-inline text-white">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('lecturer.results.upload')}}"
                            class="nav-link d-block  align-middle ps-2 {{request()->routeIs('lecturer.results.upload')? 'active': ''}} ">
                            <i class="fa-solid text-white fa-upload"></i>
                            <span class="ms-1 d-none d-sm-inline text-white">Upload Results</span>
                        </a>
                    </li> 
                    <li class="nav-item">
                        <a href="{{route('lecturer.results')}}"
                            class="nav-link d-block  align-middle ps-2 {{request()->routeIs('lecturer.results.view')? 'active': ''}} ">
                            <i class="fa-solid text-white fa-eye"></i>
                            <span class="ms-1 d-none d-sm-inline text-white">View Results</span>
                        </a>
                    </li> 
                    <li class="nav-item">
                        <a href="{{route('lecturer.profile')}}"
                            class="nav-link ps-2 align-middle {{request()->routeIs('lecturer.profile')? 'active': ''}} ">
                            <i class="fa-solid text-white fa-user"></i>
                            <span class="ms-1 d-none d-sm-inline text-white">Profile</span> </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('lecturer.changePassword')}}"
                            class="nav-link ps-2 align-middle {{request()->routeIs('lecturer.changePassword')? 'active': ''}} ">
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