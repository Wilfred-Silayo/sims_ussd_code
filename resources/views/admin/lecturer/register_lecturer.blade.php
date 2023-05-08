@extends('layouts.admin')
@section('title','Lecturers')
@section('content')

@if (session('info'))
<div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session('info') }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if (session('error'))
<div id="success-message" class="alert alert-danger" role="alert">
    <strong>{{ session('error') }}</strong>
</div>
@endif
<div class="container" style="background-color:#ffffff">
    <div class="row mt-3 align-items-baseline">
        <a href="{{route('register.lecturer.form')}}" class='btn btn-success col-1 mt-2'>
            <i class="fa fa-plus"></i> New
        </a>

        <div class="col-md-3 offset-2 mt-2">
            <h2>Lecturers</h2>
        </div>

        <div class="col-md-6">
            <form class="d-flex" action="{{ route('lecturer.search') }}" method="GET">
                <input class="form-control me-2" name="username" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>

    <div class="row mt-3">
        @if ($lecturers->isEmpty())
        <div class="alert alert-info">
            No records found. <a class="ms-2"
                href="{{route('register.lecturer')}}">{{request()->routeIs('register.lecturer')?'':'Go back'}}</a> <a
                class="ms-2" href="{{route('lecturer.dashboard')}}">Go To Dashboard</a>
        </div>
        @else
        <table id="example1" class="table table-striped table-hover">
            <thead>
                <th>Registration Number</th>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Actions</th>
            </thead>
            <tbody>
                @foreach($lecturers as $lecturer)
                <tr>
                    <td>{{$lecturer->username}}</td>
                    <td>{{$lecturer->firstname}}</td>
                    <td>{{$lecturer->lastname}}</td>
                    <td>
                        <a href="{{ route('register.lecturer.edit', str_replace('/', '-', $lecturer->username)) }}"
                            class='btn btn-success btn-sm edit btn-flat'>
                            <i class='fa fa-edit'></i> Edit
                        </a>

                        <button class='btn btn-danger btn-sm delete btn-flat' data-bs-toggle="modal"
                            data-bs-target="#delete" data-username="{{str_replace('/','-',$lecturer->username)}}"
                            data-firstname="{{$lecturer->firstname}}" data-lastname="{{$lecturer->lastname}}">
                            <i class='fa fa-trash'></i> Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        {{ $lecturers->links('layouts.paginationlinks') }}
    </div>

    <!--delete lecturer Modal -->
    <div class="modal fade" id="delete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="deleteLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Are you sure you want to delete?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Do you really want to delete <strong><span id="delete-lecturer-name"></span></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm btn-flat"
                        data-bs-dismiss="modal">Cancel</button>
                    <form id="delete-lecturer-form" method="POST"
                        action="{{ route('lecturer.destroy', ['username' => '__username__']) }}">
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
            const username = button.getAttribute('data-username');
            const firstname = button.getAttribute('data-firstname');
            const lastname = button.getAttribute('data-lastname');
            const deleteLecturerForm = document.querySelector('#delete-lecturer-form');
            const deleteLecturerName = document.querySelector('#delete-lecturer-name');
            const deleteLecturerAction = deleteLecturerForm.getAttribute('action').replace(
                '__username__', username);
            deleteLecturerForm.setAttribute('action', deleteLecturerAction);
            deleteLecturerName.textContent = `${firstname} ${lastname}`;

        });
    });
    </script>


    @endsection