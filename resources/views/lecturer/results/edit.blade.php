@extends('layouts.admin')
@section('title','Edit Results')
@section('content')

<div class="container">
    <form method="POST"
        action="{{ route('result.update', [$result->moduleCode, str_replace('/', '-',$result->studentID)]) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="moduleCode">Module Code:</label>
            <input type="text" class="form-control" name="moduleCode" id="moduleCode"
                value="{{ $result->moduleCode }}" readonly>
        </div>

        <div class="form-group">
            <label for="studentID">Student ID:</label>
            <input type="text" class="form-control" name="studentID" id="studentID" value="{{ $result->studentID }}"
                readonly>
        </div>

        <div class="form-group">
            <label for="coursework">Coursework:</label>
            <input type="number" class="form-control" name="coursework" id="coursework"
                value="{{ old('coursework', $result->Coursework) }}" readonly>
            @if ($errors->has('coursework'))
            <span class="text-danger">{{ $errors->first('coursework') }}</span>
            @endif
        </div>

        <div class="form-group">
            <label for="semester_exam">Semester Exam:</label>
            <input type="number" class="form-control" name="semester_exam" id="semester_exam"
                value="{{ old('semester_exam', $result->semesterExam) }}" readonly>
            @if ($errors->has('semester_exam'))
            <span class="text-danger">{{ $errors->first('semester_exam') }}</span>
            @endif
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" >Update</button>
        </div>
    </form>


</div>


@endsection