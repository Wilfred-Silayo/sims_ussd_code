@extends('layouts.admin')
@section('title','View Enrollment')
@section('content')
@if (session('error'))
<div id="success-message" class="alert alert-danger" role="alert">
    <strong>{{ session('error') }}</strong>
</div>
@endif
<div class="container" style="background-color:#ffffff">
    <div class="row mt-3 align-items-baseline">

        <div class="col-md-3 offset-2 mt-2">
            <h2>Enrollments</h2>
        </div>

        <div class="col-md-6">
            <form class="d-flex" action="{{ route('enrollment.search') }}" method="GET">
                <input class="form-control me-2" name="studentID" type="search" placeholder="Search by Student Reg No"
                    aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>

    <div class="row mt-3">
        @if ($enrollments->isEmpty())
        <div class="alert alert-info">
            No records found. <a class="ms-2" href="{{route('admin.dashboard')}}">Go To Dashboard</a>
        </div>
        @else
        <table id="example1" class="table table-striped table-hover">
            <thead>
                <th>Registration Number</th>
                <th>Module Code</th>
                <th>Semester </th>
                <th>academic Year</th>
            </thead>
            <tbody>
                @foreach($enrollments as $enrollment)
                <tr>
                    <td>{{$enrollment->studentID}}</td>
                    <td>{{$enrollment->moduleCode}}</td>
                    <td>{{$enrollment->semester}}</td>
                    <td>{{$enrollment->academicYear}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        {{ $enrollments->links('layouts.paginationlinks') }}
    </div>

@endsection