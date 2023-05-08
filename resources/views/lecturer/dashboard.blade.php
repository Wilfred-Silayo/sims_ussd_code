@extends('layouts.lecturer')
@section('title','Dashboard')
@section('content')
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
@endsection