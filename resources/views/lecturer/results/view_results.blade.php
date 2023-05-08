@extends('layouts.lecturer')
@section('title','Students Results')
@section('content')
<div class="container">
    @if (session('error'))
    <div id="success-message" class="alert alert-danger fade show" role="alert">
        <strong>{{ session('error') }}</strong>
    </div>
    @endif
    @if (session('success'))
    <div id="success-message" class="alert alert-success fade show" role="alert">
        <strong>{{ session('success') }}</strong>
    </div>
    @endif
    <div class="row mt-2 mb-2 align-items-baseline">
        <div class="col-md-3 offset-2 mt-2">
            <h3>Students Results </h3>
        </div>
        <div class="col-md-6">
            <form class="d-flex"
                action="{{ route('lecturer.results.searchResults',['moduleCode'=>$moduleCode,'moduleName'=>$moduleName]) }}"
                method="GET">
                <input class="form-control me-2" name="studentID" type="search" placeholder="Search"
                    aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>
    <div class="row bg-light py-3">
        <div class="col-md-3">
            {{$moduleCode}}
        </div>
        <div class="col-md-9">
            {{$moduleName}}
        </div>
    </div>
    <div class="row mt-3">
        @if (!isset($students) || $students->isEmpty())
        <div class="alert alert-info">
            No records found. <a class="ms-2" href="{{route('student.dashboard')}}">Go To Dashboard</a>
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
                @foreach($students as $student)
                <tr>
                    <td>{{ $student->studentID }}</td>
                    <td>{{$student->Coursework}}</td>
                    <td>{{$student->semesterExam}}</td>
                    <td>
                        <a href="{{route('result.edit',['studentID'=>str_replace('/','-',$student->studentID),'moduleCode'=>$moduleCode,'moduleName'=>$moduleName ])}}"
                            class='btn btn-success btn-sm edit btn-flat'>
                            <i class='fa fa-edit'></i> Edit
                        </a>
                        <button class='btn btn-danger btn-sm delete btn-flat' data-bs-toggle="modal"
                            data-bs-target="#delete" data-moduleCode="{{$moduleCode}}"
                            data-studentID="{{str_replace('/','-',$student->studentID)}}">
                            <i class='fa fa-trash'></i> Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        {{ $students->links('layouts.paginationlinks') }}
    </div>
</div>

<!--delete student Modal -->
<div class="modal fade" id="delete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Are you sure you want to delete results for?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Do you really want to delete results for <strong><span id="delete-result-name"></span></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm btn-flat" data-bs-dismiss="modal">Cancel</button>
                <form id="delete-result-form" method="POST"
                    action="{{ route('result.destroy', ['studentID' => '__studentID__','moduleCode'=>$moduleCode]) }}">
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