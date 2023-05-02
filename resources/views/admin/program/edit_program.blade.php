@extends('layouts.admin')
@section('title', 'Edit Program')
@section('content')
<div class="container justify-content-center">
    <h5 class="mb-2" id="registerLabel">Edit Program</h5>
    <hr>
    <form action="{{ route('program.update', ['programID' => $program->programID]) }}" id="edit-program-form"
        method="POST">
        @csrf
        @method('PUT')
        <div class="row mb-3">
            <label for="programID" class="col col-form-label form-label">{{ __('Program ID') }}</label>
            <div class="col-8">
                <input id="programID" type="text" class="form-control @error('programID') is-invalid @enderror"
                    value="{{ old('programID', $program->programID) }}" name="programID" autocomplete="programID">
                @error('programID')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="programname" class="col col-form-label form-label">{{ __('Program Name') }}</label>
            <div class="col-8">
                <input id="programname" type="text" class="form-control @error('programname') is-invalid @enderror"
                    value="{{ old('programname', $program->programname) }}" name="programname"
                    autocomplete="programname">
                @error('programname')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="capacity" class="col col-form-labelform-label">{{__('Capacity')}}</label>
            <div class="col-8">
                <input id="capacity" type="number" class="form-control @error('capacity') is-invalid @enderror"
                    value="{{ old('capacity',$program->capacity) }}" name="capacity" autocomplete="capacity">
                @error('capacity')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="ntalevel" class="col col-form-labelform-label">{{__('NTA Level')}}</label>
            <div class="col-8">
                <input id="ntalevel" type="number" class="form-control @error('ntalevel') is-invalid @enderror"
                    value="{{ old('ntalevel',$program->ntalevel) }}" name="ntalevel" autocomplete="ntalevel">
                @error('ntalevel')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="department" class="col col-form-labelform-label">{{__('Department Name')}}</label>
            <div class="col-8">
                <input id="department" type="text" class="form-control @error('department') is-invalid @enderror"
                    value="{{ old('department',$program->department) }}" name="department" autocomplete="department">
                @error('department')
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
    document.getElementById("edit-program-form").submit();
}
</script>
@endsection