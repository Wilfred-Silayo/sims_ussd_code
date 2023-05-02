@extends('layouts.admin')
@section('title', 'Edit Module')
@section('content')
<div class="container justify-content-center">
    <h5 class="mb-2" id="registerLabel">Edit Module</h5>
    <hr>
    <form action="{{ route('module.update', ['modulecode' => $module->modulecode]) }}" id="edit-module-form"
        method="POST">
        @csrf
        @method('PUT')
        <div class="row mb-3">
            <label for="modulecode" class="col col-form-label form-label">{{ __('Module Code') }}</label>
            <div class="col-8">
                <input id="modulecode" type="text" class="form-control @error('modulecode') is-invalid @enderror"
                    value="{{ old('modulecode', $module->modulecode) }}" name="modulecode" autocomplete="modulecode">
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
                    value="{{ old('modulename',$module->modulename) }}" name="modulename" autocomplete="modulename">
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
                    value="{{ old('credit',$module->credit) }}" name="credit" autocomplete="credit">
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
                    <option value="{{old('elective',$module->elective)}}">{{$module->elective}}</option>
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
                    value="{{ old('semester',$module->semester) }}" placeholder="1 or 2" name="semester" autocomplete="semester">
                @error('semester')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="program" class="col col-form-label form-label">{{ __('Program') }}</label>
            <div class="col-8">
                <select class="form-control @error('program') is-invalid @enderror" id="program"
                    name="program">
                    @foreach($programs as $program)
                    <option value="{{ $program->programID }}"
                        {{ old('program', $module->program) == $program->programID ? 'selected' : '' }}>
                        {{ $program->programname}}
                    </option>
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
            <label for="department" class="col col-form-label form-label">{{ __('Department') }}</label>
            <div class="col-8">
                <select class="form-control @error('department') is-invalid @enderror" id="department"
                    name="department">
                    @foreach($departments as $department)
                    <option value="{{ $department->deptcode }}"
                        {{ old('department', $module->department) == $department->deptcode ? 'selected' : '' }}>
                        {{ $department->deptname}}
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
            <label for="lecturerID" class="col col-form-labelform-label">{{__('Lecturer ')}}</label>
            <div class="col-8">
                <select class="form-control @error('lecturerID') is-invalid @enderror" id="lecturerID"
                    name="lecturerID">
                    @if(isset($module) && (!isset($module->lecturerID) || (is_string($module->lecturerID) &&
                    empty($module->lecturerID))))
                    <option value="">Select a lecturer</option>
                    @endif

                    @foreach($lecturers as $lecturer)
                    <option value="{{ $lecturer->username }}"
                        {{ old('lecturerID', $module->lecturerID) == $lecturer->username ? 'selected' : '' }}>
                        {{ $lecturer->username }} : {{ $lecturer->firstname }} {{ $lecturer->lastname }}
                    </option>
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
        <button type="button" class="btn btn-success btn-sm btn-flat" onclick="sendForm()">Update</button>
    </div>
</div>
</form>
</div>
<script>
function sendForm() {
    document.getElementById("edit-module-form").submit();
}
</script>
@endsection