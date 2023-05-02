@extends('layouts.app')
@section('title','Dashboard')
@section('content')
<div class="container mb-4">
    <h5> Welcome Back: <span class="text-primary"> {{Auth::guard('web')->user()->firstname}}
            {{Auth::guard('web')->user()->lastname}}</span></h5>
</div>
<div class="container">
    <div class="row d-flex justify-content-between mb-2">
        @if(!empty($academicYear))
        <div class="col">
            <h6>Academic Year: {{$academicYear->year}}</h6>
        </div>
        <div class="col">
            <h6>Semester: {{$academicYear->semester}}</h6>
        </div>
        @endif
        <div class="col">
            <h6>Date of Today: {{ $dateOfToday}}</h6>
        </div>
    </div>
    <hr>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-8 ">
            <p>Registered for:</p>
            <h6>{{$programName}} [{{$programID}}]</h6>
        </div>
      

    </div>
</div>

@endsection