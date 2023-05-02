@extends('layouts.app')
@section('title','Full Profile')
@section('content')
<div class="container">
<div class="row">
        @if (session('info'))
        <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('info') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <div class="col-md-4 ">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Upload Profile Photo</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="div">
                        <div class="div">
                            <img src="{{ asset('storage/'.$user->profile_photo_path) }}" alt="Profile Photo" width="60"
                                height="60" style="object-fit: cover; border-radius: 50%;">
                        </div>

                    </div>
                    <form method="POST" action="{{route('student.profile.store')}}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="profile_photo" class="mb-2">Choose Profile Photo</label>
                            <input type="file" name="profile_photo"
                                class=" mb-2 form-control-file @error('profile_photo') is-invalid @enderror"
                                id="profile_photo" value="{{ old('profile_photo',$user->profile_photo_path) }}">
                            @error('profile_photo')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">Upload</button>

                    </form>
                </div>
            </div>
        </div>

</div>
@endsection
