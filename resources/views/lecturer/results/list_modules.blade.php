@extends('layouts.lecturer')
@section('title','Modules')
@section('content')

@if (session('info'))
<div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session('success') }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="container" style="background-color:#ffffff">
    <div class="row mt-3 align-items-baseline">

        <div class="col-md-3 offset-2 mt-2">
            <h2>Results</h2>
        </div>

        <div class="col-md-6">
            <form class="d-flex" action="{{ route('lecturer.module.search') }}" method="GET">
                <input class="form-control me-2" name="modulecode" type="search" placeholder="Search by Student Reg No"
                    aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>

    <div class="row mt-3">
        @if ($modules->isEmpty())
        <div class="alert alert-info">
            No records found. <a class="ms-2" href="{{route('lecturer.dashboard')}}">Go To Dashboard</a>
        </div>
        @else
        <table id="example1" class="table table-striped table-hover">
            <thead>
                <th>Module Code</th>
                <th>Module Name</th>
                <th>Credit</th>
                <th>Elective</th>
                <th>For Program</th>
            </thead>
            <tbody>
                @foreach($modules as $module)
                <tr>
                    <td>{{$module->modulecode}}</td>
                    <td>{{$module->modulename}}</td>
                    <td>{{$module->credit}}</td>
                    <td>{{$module->elective}}</td>
                    <td>{{$module->program}}</td>
                    <td>
                        <a href="{{ route('lecturer.module.view', $module->modulecode) }}"
                            class='btn btn-success btn-sm edit btn-flat'>
                            <i class='fa fa-eye'></i> View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        {{ $modules->links('layouts.paginationlinks') }}
    </div>

    @endsection