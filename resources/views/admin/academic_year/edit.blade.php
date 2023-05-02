@extends('layouts.admin')
@section('title','Edit Academic Year')
@section('content')
<div class="container">
    <div class="row mt-4">
        <div class="col-sm-4 mb-2">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <h5 class="card-title">Academic Year</h5>
                    <form method="POST" action="{{ route('academic-year.update', $academicYear->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-2">
                            <label for="year">Year:</label>
                            <input type="text" class="form-control" name="year" id="year"
                                value="{{ old('year', $academicYear->year) }}" required>
                            @if ($errors->has('year'))
                            <span class="text-danger">{{ $errors->first('year') }}</span>
                            @endif
                        </div>
                        <div class="form-group mb-2">
                            <label for="semester">Semester:</label>
                            <select class="form-control" name="semester" id="semester" required>
                                <option value="1" {{ $academicYear->semester == 1 ? 'selected' : '' }}>1st Semester
                                </option>
                                <option value="2" {{ $academicYear->semester == 2 ? 'selected' : '' }}>2nd Semester
                                </option>
                            </select>
                            @if ($errors->has('semester'))
                            <span class="text-danger">{{ $errors->first('semester') }}</span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection