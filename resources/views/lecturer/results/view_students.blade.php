@extends('layouts.lecturer')
@section('title','My students')
@section('content')
<div class="container">
    @if (session('error'))
    <div id="success-message" class="alert alert-danger fade show" role="alert">
        <strong>{{ session('error') }}</strong>
    </div>
    @endif
    <div class="row mt-2 mb-2 align-items-baseline">
        <div class="col-md-3 offset-2 mt-2">
            <h3>My Students </h3>
        </div>
        <div class="col-md-6">
            <form class="d-flex"
                action="{{ route('lecturer.module.searchStudents',['moduleCode'=>$moduleCode,'moduleName'=>$moduleName]) }}"
                method="GET">
                <input class="form-control me-2" name="studentID" type="search" placeholder="Search"
                    aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>
    <div class="row bg-light py-3">
        <div class="col-md-3">
            {{$moduleCode}}
        </div>
        <div class="col-md-9">
            {{$moduleName}}
        </div>
    </div>
    <div class="row mt-3">
        @if (!isset($students) || $students->isEmpty())
        <div class="alert alert-info">
            No records found. <a class="ms-2"
                href="{{route('lecturer.results.upload')}}">{{request()->routeIs('lecturer.results.upload')?'':'Go back'}}</a> <a
                class="ms-2" href="{{route('student.dashboard')}}">Go To Dashboard</a>
        </div>
        @else
        <table id="example1" class="table table-striped table-hover">
            <thead>
                <th>Registration Number</th>
                <th>Firstname</th>
                <th>Lastname</th>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td>{{ $student->studentID }}</td>
                    <td>{{$student->firstname}}</td>
                    <td>{{$student->lastname}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        {{ $students->links('layouts.paginationlinks') }}
    </div>
</div>
@endsection