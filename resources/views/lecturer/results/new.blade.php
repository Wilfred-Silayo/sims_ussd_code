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
        <a href="{{route('module.result.form')}}" class='btn btn-success col-1 mt-2'>
            <i class="fa fa-plus"></i> New
        </a>

        <div class="col-md-3 offset-2 mt-2">
            <h2>Results</h2>
        </div>

        <div class="col-md-6">
            <form class="d-flex" action="{{ route('result.search',$result->moduleCode) }}" method="GET">
                <input class="form-control me-2" name="studentID" type="search" placeholder="Search by Student Reg No"
                    aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>

    <div class="row mt-3">
        @if ($results->isEmpty())
        <div class="alert alert-info">
            No records found. <a class="ms-2"
                href="{{route('result.search')}}">{{request()->routeIs('result.search')?'':'Go back'}}</a> <a
                class="ms-2" href="{{route('lecturer.dashboard')}}">Go To Dashboard</a>
        </div>
        @else
        <table id="example1" class="table table-striped table-hover">
            <thead>
                <th>Registration Number</th>
                <th>Course Work</th>
                <th>Semester Exam</th>
                <th>Actions</th>
            </thead>
            <tbody>
                @foreach($results as $result)
                <tr>
                    <td>{{$result->studentID}}</td>
                    <td>{{$result->Coursework}}</td>
                    <td>{{$result->semesterExam}}</td>
                    <td>
                        <a href="{{ route('module.result.edit', ['studentID'=>str_replace('/', '-', $result->studentID),
                            'moduleCode'=>$result->moduleCode]) }}" class='btn btn-success btn-sm edit btn-flat'>
                            <i class='fa fa-edit'></i> Edit
                        </a>

                        <button class='btn btn-danger btn-sm delete btn-flat' data-bs-toggle="modal"
                            data-bs-target="#delete" data-studentID="{{str_replace('/','-',$result->studentID)}}"
                            data-moduleCode="{{$result->moduleCode}}" >
                            <i class='fa fa-trash'></i> Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        {{ $results->links('layouts.paginationlinks') }}
    </div>

    <!--delete result Modal -->
    <div class="modal fade" id="delete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="deleteLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Are you sure you want to delete?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Do you really want to delete <strong><span id="delete-result-name"></span></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm btn-flat"
                        data-bs-dismiss="modal">Cancel</button>
                    <form id="delete-result-form" method="POST"
                        action="{{ route('result.destroy', ['studentID' => '__studentID__','moduleCode'=>$result->moduleCode]) }}">
                        @csrf
                        @method('DELETE')
                        <button class='btn btn-danger btn-sm delete btn-flat' type="submit"
                            data-bs-dismiss="modal">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    const deleteButtons = document.querySelectorAll('.delete');

    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            const studentID = button.getAttribute('data-studentID');
            const moduleCode = button.getAttribute('data-moduleCode');
            const deleteResultForm = document.querySelector('#delete-result-form');
            const deleteResultName = document.querySelector('#delete-result-name');
            const deleteResultAction = deleteResultForm.getAttribute('action').replace(
                '__studentID__', studentID);
            deleteResultForm.setAttribute('action', deleteResultAction);
            deleteResultName.textContent = `${studentID}`;

        });
    });
    </script>


    @endsection