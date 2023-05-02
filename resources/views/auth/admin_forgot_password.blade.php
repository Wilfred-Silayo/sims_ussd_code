@extends('layouts.app')
@section('title','Admin Forgot Password')
@section('content')
<div class="row d-flex justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('Lecturer Reset Password') }}</div>

            <div class="card-body">
                <form method="POST" action="{{ route('admin.password.email') }}">
                    @csrf
                    <p>Enter your Email Adress and we will send you a link to reset your password.</p>
                    @if (Session::get('fail'))
                    <div class="alert alert-danger">
                        {{ Session::get('fail') }}
                    </div>
                    @endif

                    @if (Session::get('success'))
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>
                    @endif
                    <div class="row mb-3">
                        <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Adress') }}</label>

                        <div class="col-md-7">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" autocomplete="email" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-secondary">
                                {{ __('Send Reset Password link') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection