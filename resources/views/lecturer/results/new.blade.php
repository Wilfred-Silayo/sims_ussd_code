@extends('layouts.lecturer')
@section('title','Results Upload')
@section('content')
@if (session('success'))
<div id="success-message" class="alert alert-success " role="alert">
    <strong>{{ session('success') }}</strong>
</div>
@endif
@if (session('error'))
<div id="error-message" class="alert alert-danger " role="alert">
    <strong>{{ session('error') }}</strong>
</div>
@endif
<div class="container">
    <h3>Upload Results </h3>
    <div class="row bg-light py-3">
        <div class="col-md-3">
            {{$moduleCode}}
        </div>
        <div class="col-md-9">
            {{$moduleName}}
        </div>
    </div>
    <div class="row bg-light">
        <form action="{{route('lecturer.module.newResult',$moduleCode)}}" method="POST">
            @csrf
            <div class="form-group row pt-3">
                <label for="studentID" class="col-sm-4 col-form-label">Student Registration Number</label>
                <div class="col-sm-10">
                    <input type="text" id="studentID" class="form-control  @error('studentID') is-invalid @enderror"
                        name="studentID" value="{{ old('studentID') }}" autocomplete="studentID" autofocus
                        placeholder="Enter Student Registration Number">
                    @error('studentID')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row pt-3">
                <label for="Coursework" class="col-sm-4 col-form-label">Course work</label>
                <div class="col-sm-10">
                    <input type="number" id="Coursework" class="form-control  @error('Coursework') is-invalid @enderror"
                        name="Coursework" value="{{ old('Coursework') }}" autocomplete="Coursework" autofocus
                        placeholder="Enter the course work marks">
                    @error('Coursework')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row pt-3">
                <label for="semesterExam" class="col-sm-4 col-form-label">Semester Exam</label>
                <div class="col-sm-10">
                    <input type="number" id="semesterExam"
                        class="form-control  @error('semesterExam') is-invalid @enderror" name="semesterExam"
                        value="{{ old('semesterExam') }}" autocomplete="semesterExam" autofocus
                        placeholder="Enter the semester Exam marks">
                    @error('semesterExam')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row pt-3 d-flexpt-3 justify-content-start">
                <div class="col-sm-10 ">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
    <div class="row mt-2 bg-secondary mb-4">
        <h4 class="text-white">Use Excel To import multiple records</h4>
        <img src="{{asset('images/excel_sample.jpg')}}" alt="excel sample">
        <div class="row bg-light mt-4">
            <div class="col-md-6">
                <h5 class="text-primary">Note:</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Your excel file should have headers in the first row as above</li>
                    <li class="list-group-item">Consider The column Name. Each column name must read as it is shown in
                        the excel above</li>
                </ul>

                <style>
                .list-group-flush .list-group-item::before {
                    content: "•";
                    margin-right: 0.5rem;
                }
                </style>
            </div>
            <div class="col-md-6">
                <form action="{{route('lecturer.results.upload.excel',$moduleCode)}}" method="POST"
                    enctype="multipart/form-data">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    @csrf
                    <div class="mb-3">
                        <label for="formFile" class="form-label">
                            <h5 class="text-primary">Import Excel file only</h5>
                        </label>
                        <div class="col">
                            <input class="form-control @error('file') is-invalid @enderror" type="file" id="formFile"
                                name="file">
                            @error('file')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary mt-2">Import</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection