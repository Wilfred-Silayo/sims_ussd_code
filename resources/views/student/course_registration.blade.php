@extends('layouts.app')
@section('title','Course Registration')
@section('content')
@if (session('success'))
<div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session('success') }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if (session('error'))
<div id="error-message" class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>{{ session('error') }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="container border-bottom border-2 mb-3">
    <h6>Course Registration </h6>
    <div class="col d-flex justify-content-between">
        <p>Semester: <span class="text-danger">{{$currentSemester}}</span></p>
        <p>Academic Year:<span class="text-danger"> {{$currentYear}}</span></p>
    </div>
</div>

<div class="container">
    <!-- Display Non-Elective Courses -->
    <h6>Non-Elective Courses</h6>
    @if($nonElectiveCourses->count() > 0)
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Credit </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($nonElectiveCourses as $course)
            <tr>
                <td>{{ $course->moduleCode }}</td>
                <td>{{ $course->modulename }}</td>
                <td>{{ $course->credit }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>There are no non-elective courses available to enroll.</p>
    @endif

    <!-- Display Elective Courses -->
    <h6>Elective Courses</h6>
    @if($electiveCourses->count() > 0)
    <form method="post" action="{{route('enrollment.store')}}">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Course Code</th>
                    <th>Course Name</th>
                    <th>Credit </th>
                    <th>Select</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($electiveCourses as $course)
                <tr>
                    <td>{{ $course->modulecode }}</td>
                    <td>{{ $course->modulename }}</td>
                    <td>{{ $course->credit }}</td>
                    <td>
                        <div class="form-check">
                            <input type="checkbox" name="course[]" value="{{$course->modulecode}}"
                                class="form-check-input"
                                {{ in_array($course->modulecode, $enrolledModules) ? 'checked' : '' }}>
                            <label class="form-check-label"></label>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </div>
    </form>
    @else
    <p>There are no elective courses available to enroll.</p>
    @endif
</div>
@endsection