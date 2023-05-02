@extends('layouts.lecturer')
@section('title', 'Profile')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Profile Photo</h3>
                </div>
                <div class="card-body">
                    <img src="{{ asset('storage/'.$user->profile_photo_path) }}" alt="Profile Photo" width="60"
                        height="60" style="object-fit: cover; border-radius: 50%;">
                </div>
                <div class="card-footer">
                    <a href="{{ route('lecturer.profile.edit') }}" class="btn btn-primary">Update Photo</a>
                </div>
            </div>
        </div>
        <div class="col-md-7 offset-2">
            <div class="row">
                <h3 class="card-title text-center">Profile Information</h3>
            </div>
            <div class="col">
                <table class="table table-bordered table-striped">
                    <tr>
                        <td>Username</td>
                        <td>{{$user->username}}</td>
                    </tr>
                    <tr>
                        <td>Full Name</td>
                        <td>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}}</td>
                    </tr>
                    <tr>
                        <td>Email Address</td>
                        <td>{{$user->email}}</td>
                    </tr>
                    <tr>
                        <td>Phone Number</td>
                        <td>{{$user->phone}}</td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td>{{$user->gender}}</td>
                    </tr>
                    <tr>
                        <td>Marital status</td>
                        <td>{{$user->maritalstatus}}</td>
                    </tr>
                    <tr>
                        <td>Date of birth</td>
                        <td>{{$user->dob}}</td>
                    </tr>
                    <tr>
                        <td>Nationality</td>
                        <td>{{$user->nationality}}</td>
                    </tr>
                </table>
                
            </div>

        </div>
    </div>
    @endsection