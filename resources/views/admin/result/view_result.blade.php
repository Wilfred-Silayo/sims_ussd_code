@extends('layouts.admin')
@section('title','Results')
@section('content')

<div class="container">
    <table class="table table-bordered table-striped">
        <tr>
            <td>Registration Number</td>
            <td>{{$results->studentID}}</td>
        </tr>
        <tr>
            <td>Module Code</td>
            <td>{{$results->moduleCode}}</td>
        </tr>
        <tr>
            <td>Module Credit</td>
            <td>{{$credit}}</td>
        </tr>
        <tr>
            <td>Semester</td>
            <td>{{$results->semester}}</td>
        </tr>
        <tr>
            <td>Academic Year</td>
            <td>{{$results->academicYear}}</td>
        </tr>
        <tr>
            <td>Course Work</td>
            <td>{{$results->Coursework}}</td>
        </tr>
        <tr>
            <td>Semester Exam</td>
            <td>{{$results->semesterExam}}</td>
        </tr>
        <tr>
            <td>Published</td>
            <td>
                @if($results->published)
                 {{'yes'}}
                 @else
                 {{'no'}}
                @endif
            </td>
        </tr>

    </table>
</div>

@endsection