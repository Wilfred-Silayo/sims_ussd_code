@extends('layouts.admin')
@section('title','Register Module')
@section('content')
<div class="container justify-content-center">
    <h5 class="mb-2" id="registerLabel">Register Module</h5>
    <hr>
    <form action="{{route('module.store')}}" id="register-module-form" method="POST">
        @csrf
        <div class="row mb-3">
            <label for="modulecode" class="col col-form-labelform-label">{{__('Module Code')}}</label>
            <div class="col-8">
                <input id="modulecode" type="text" class="form-control @error('modulecode') is-invalid @enderror"
                    value="{{ old('modulecode') }}" name="modulecode" autocomplete="modulecode">
                @error('modulecode')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="modulename" class="col col-form-labelform-label">{{__('Module Name')}}</label>
            <div class="col-8">
                <input id="modulename" type="text" class="form-control @error('modulename') is-invalid @enderror"
                    value="{{ old('modulename') }}" name="modulename" autocomplete="modulename">
                @error('modulename')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="credit" class="col col-form-labelform-label">{{__('Module Credit')}}</label>
            <div class="col-8">
                <input id="credit" type="number" class="form-control @error('credit') is-invalid @enderror"
                    value="{{ old('credit') }}" name="credit" autocomplete="credit">
                @error('credit')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="elective" class="col col-form-labelform-label">{{__('Module elective')}}</label>
            <div class="col-8">
                <select class="form-control @error('elective') is-invalid @enderror" id="elective" name="elective">
                    <option value="{{old('elective')?? ''}}">Is Module elective?</option>
                    <option value="no">No</option>
                    <option value="yes">Yes</option>
                </select>
                @error('elective')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="semester" class="col col-form-labelform-label">{{__('Semester')}}</label>
            <div class="col-8">
                <input id="semester" type="number" class="form-control @error('semester') is-invalid @enderror"
                    value="{{ old('semester') }}" placeholder="1 or 2" name="semester" autocomplete="semester">
                @error('semester')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="department" class="col col-form-labelform-label">{{__('Department')}}</label>
            <div class="col-8">
                <select class="form-control @error('department') is-invalid @enderror" id="department"
                    name="department">
                    <option value="{{old('department')?? ''}}">Select Department</option>
                    @foreach($departments as $department)
                    <option value="{{$department->deptcode}}"> {{$department->deptname}}
                    </option>
                    @endforeach
                </select>
                @error('department')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="program" class="col col-form-labelform-label">{{__('Program')}}</label>
            <div class="col-8">
                <select class="form-control @error('program') is-invalid @enderror" id="program" name="program">
                    <option value="{{old('program')?? ''}}">Select Program</option>
                    @foreach($programs as $program)
                    <option value="{{$program->programID}}">{{$program->programname}} </option>
                    @endforeach
                </select>
                @error('program')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="lecturerID" class="col col-form-labelform-label">{{__('Lecturer <optional>')}}</label>
            <div class="col-8">
                <select class="form-control @error('lecturerID') is-invalid @enderror" id="lecturerID"
                    name="lecturerID">
                    <option value="{{old('lecturerID')?? ''}}">Select lecturer</option>
                    @foreach($lecturers as $lecturer)
                    <option value="{{$lecturer->username}}">{{$lecturer->username}} {{$lecturer->firstname}}
                        {{$lecturer->lastname}}</option>
                    @endforeach
                </select>
                @error('lecturerID')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
</div>

<div class="row mb-4 justify-content-end">
    <div class="col-4 mb-4">
        <button type="button" class="btn btn-success btn-sm btn-flat" onclick="sendForm()">Register</button>
    </div>
</div>
</div>
<script>
function sendForm() {
    document.getElementById("register-module-form").submit();
}
</script>
@endsection