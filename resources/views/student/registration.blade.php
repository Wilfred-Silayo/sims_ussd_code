@extends('layouts.app')
@section('title','Registration')
@section('content')
<div class="container">
    <table class="table table-bordered table-striped">
        <tr>
            <td>Admission Number</td>
            <td>{{$user->admission}}</td>
        </tr>
        <tr>
            <td>Registration Number</td>
            <td>{{$user->username}}</td>
        </tr>
        <tr>
            <td>Full Name</td>
            <td>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}}</td>
        </tr>
        <tr>
            <td>Program</td>
            <td>{{$user->program}}</td>
        </tr>
        <tr>
            <td>Year of study</td>
            <td>{{$user->yearofstudy}}</td>
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
    
@endsection