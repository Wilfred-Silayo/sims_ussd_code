@extends('layouts.lecturer')
@section('title','Dashboard')
@section('content')
<div class="container">
    <div class="row d-flex justify-content-between mb-2">
        <div class="col">
            <h6>Academic Year: {{$academicYear}}</h6>
        </div>
        <div class="col">
            <h6>Date of Today: {{ $dateOfToday}}</h6>
        </div>
    </div>
    <hr>
</div>
@endsection