@extends('layouts.admin')
@section('title','New Academic Year')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create Academic Year') }}</div>

                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    <form method="POST" action="{{ route('academic_year.store') }}">
                        @csrf

                        <div class="form-group row mb-3">
                            <label for="year" class="col-md-4 col-form-label text-md-right">{{ __('Year') }}</label>

                            <div class="col-md-6">
                                <input id="year" type="text" class="form-control @error('year') is-invalid @enderror"
                                    placeholder="enter academic year eg. 2020/2021" name="year"
                                    value="{{ old('year') }}" autocomplete="year" autofocus>

                                @error('year')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="semester"
                                class="col-md-4 col-form-label text-md-right">{{ __('Semester') }}</label>

                            <div class="col-md-6">
                                <input id="semester" type="text"
                                    class="form-control @error('semester') is-invalid @enderror" name="semester"
                                    placeholder="enter semester eg. 1" value="{{ old('semester') }}"
                                    autocomplete="semester">

                                @error('semester')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection