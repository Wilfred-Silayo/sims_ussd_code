@extends('layouts.admin')
@section('title','Dashboard')
@section('content')

@if (session('success'))
<div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session('success') }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if (session('error'))
<div id="success-message" class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>{{ session('error') }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
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
    <div class="row mt-4">
        <div class="col-sm-4 mb-2 ">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total students</h5>
                    <p class="card-text"><strong>{{$studentsCount}}</strong></p>
                    <a href="{{route('register.student')}}" class="btn btn-info">Go To Students</a>
                </div>
            </div>
        </div>
        <div class="col-sm-4 mb-2 ">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total lecturers</h5>
                    <p class="card-text"><strong>{{$lecturersCount}}</strong></p>
                    <a href="{{route('register.lecturer')}}" class="btn btn-info">Go To Lecturers</a>
                </div>
            </div>
        </div>
        <div class="col-sm-4 mb-2 ">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Programs</h5>
                    <p class="card-text"><strong>{{$programsCount}}</strong></p>
                    <a href="{{route('register.program')}}" class="btn btn-info">Go To Programs</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-sm-4 mb-2 ">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total modules</h5>
                    <p class="card-text"><strong>{{$modulesCount}}</strong></p>
                    <a href="{{route('register.module')}}" class="btn btn-info">Go To Modules</a>
                </div>
            </div>
        </div>
        <div class="col-sm-4 mb-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total departments</h5>
                    <p class="card-text"><strong>{{$departmentsCount}}</strong></p>
                    <a href="{{route('register.department')}}" class="btn btn-info">Go To Departments</a>
                </div>
            </div>
        </div>
        <div class="col-sm-4 mb-2 ">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title ">Results</h5>
                    <div class="div mt-4 mb-3 d-flex justify-content-between align-items-baseline">
                        @if($published)
                        <form action="{{ route('result.unpublish') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Unpublish All</button>
                        </form>
                        @else
                        <form action="{{ route('result.publish') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-info">Publish All</button>
                        </form>
                        @endif
                        <a href="{{route('student.results')}}" class="btn mt-2 btn-info ">Go To Results</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-sm-4 mb-2 ">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Academic Year</h5>
                    <div class="mt-4 mb-3 d-flex justify-content-between align-items-baseline">
                        @if(!empty($academicYear))
                        <a href="{{ route('academic_year.index') }}" class="btn btn-info">Update</a>
                        @endif
                        <a href="{{ route('academic_year.create') }}" class="btn btn-info">New</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4 mb-2">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Enroll Students</h5>
                        <div class=" mt-4 mb-3 d-flex justify-content-between align-items-baseline">
                        <form action="{{ route('enrollment.enroll') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-info">Enroll</button>
                        </form>
                        <a href="{{route('enrollment.view')}}" class="btn mt-2 btn-info ">Go To Enrollments</a>
                    </div>
                </div>
            </div>
    </div>
</div>

@endsection