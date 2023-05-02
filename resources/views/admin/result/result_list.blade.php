@extends('layouts.admin')
@section('title','Results')
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
            <form class="d-flex" action="{{ route('result.search') }}" method="GET">
                <input class="form-control me-2" name="studentID" type="search" placeholder="Search by Student Reg No"
                    aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>

    <div class="row mt-3">
        @if ($results->isEmpty())
        <div class="alert alert-info">
            No records found. <a class="ms-2" href="{{route('admin.dashboard')}}">Go To Dashboard</a>
        </div>
        @else
        <table id="example1" class="table table-striped table-hover">
            <thead>
                <th>Registration Number</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Program</th>
                <th>View</th>
            </thead>
            <tbody>
                @foreach($results as $result)
                <tr>
                    <td>{{$result->username}}</td>
                    <td>{{$result->firstname}}</td>
                    <td>{{$result->lastname}}</td>
                    <td>{{$result->program}}</td>
                    <td>
                        <a href="{{ route('student.result.view', ['studentID'=>str_replace('/', '-', $result->username),
                            ]) }}" class='btn btn-success btn-sm edit btn-flat'>
                            <i class='fa fa-eye'></i> View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        {{ $results->links('layouts.paginationlinks') }}
    </div>

    @endsection