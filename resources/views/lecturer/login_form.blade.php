@extends('layouts.lecturer')
@section('title','Lecturer Login')
@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-around mt-4">

        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">{{ __('Lecturer Login') }}</div>

                <div class="card-body bg-light">
                    <form method="POST" action="{{ route('lecturer.login') }}">
                        @if (Session::get('error'))
                        <div class="alert alert-danger">
                            {{ Session::get('error') }}
                        </div>
                        @endif

                        @if (Session::get('info'))
                        <div class="alert alert-info">
                            {{ Session::get('info') }}
                        </div>
                        @endif
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                <input type="text" id="username"
                                    class="form-control @error('username') is-invalid @enderror" name="username"
                                    value="{{ old('username') }}" autocomplete="username" autofocus
                                    placeholder="Enter username">
                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    placeholder="Enter password" autofocus>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-light rounded-left rounded-right btn-eye"
                                        id="togglePassword"><i class="fa fa-eye"></i></button>
                                </div>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 ">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        value="{{ old('remember') ? 'checked' : '' }}">

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn offset-4 btn-secondary">
                                    {{ __('Login') }}
                                </button>
                                @if (Route::has('password.request'))
                                <a class="btn btn-link ms-4 ps-4" href="{{ route('lecturer.password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                    <div class="mt-4">
                        <hr>
                    </div>
                    <h5 class="text-center my-3">or</h5>
                    <div class="row">
                        <div class="col-6 text-center">
                            <a href="{{route('login')}}" class="btn btn-secondary">Login as Student</a>
                        </div>
                        <div class="col-6 text-center">
                            <a href="{{route('admin.login.form')}}" class="btn btn-secondary">Login as Admin</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
// get the password input element
var passwordField = document.getElementById('password');

// get the eye button element
var togglePasswordButton = document.getElementById('togglePassword');

// add a click event listener to the button
togglePasswordButton.addEventListener('click', function() {
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
    } else {
        passwordField.type = 'password';
    }
});
</script>
@endsection