@extends('layouts.admin')
@section('title','Register Department')
@section('content')
<div class="container justify-content-center">
    <h5 class="mb-2" id="registerLabel">Register department</h5>
    <hr>
    <form action="{{route('department.store')}}" id="register-department-form" method="POST">
        @csrf
        <div class="row mb-3">
            <label for="deptcode" class="col col-form-labelform-label">{{__('Department Code')}}</label>
            <div class="col-8">
                <input id="deptcode" type="text" class="form-control @error('deptcode') is-invalid @enderror"
                    value="{{ old('deptcode') }}" name="deptcode" autocomplete="deptcode">
                @error('deptcode')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="deptname" class="col col-form-labelform-label">{{__('Department Name')}}</label>
            <div class="col-8">
                <input id="deptname" type="text" class="form-control @error('deptname') is-invalid @enderror"
                    value="{{ old('deptname') }}" name="deptname" autocomplete="deptname">
                @error('deptname')
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
    document.getElementById("register-department-form").submit();
}
</script>
@endsection