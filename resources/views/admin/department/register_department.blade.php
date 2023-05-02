@extends('layouts.admin')
@section('title','Departments')
@section('content')

@if (session('info'))
<div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session('info') }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="container" style="background-color:#ffffff">
    <div class="row mt-3 align-items-baseline">
        <a href="{{route('register.department.form')}}" class='btn btn-success col-1 mt-2'>
            <i class="fa fa-plus"></i> New
        </a>

        <div class="col-md-3 offset-2 mt-2">
            <h2>Departments</h2>
        </div>

        <div class="col-md-6">
            <form class="d-flex" action="{{ route('department.search') }}" method="GET">
                <input class="form-control me-2" name="deptcode" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>


    <div class="row mt-3">
        @if ($departments->isEmpty())
        <div class="alert alert-info">
            No records found. <a class="ms-2"
                href="{{route('register.department')}}">{{request()->routeIs('register.department')?'':'Go back'}}</a>
            <a class="ms-2" href="{{route('admin.dashboard')}}">Go To Dashboard</a>
        </div>
        @else
        <table id="example1" class="table table-striped table-hover">
            <thead>
                <th>Department Code</th>
                <th>Department Name</th>
                <th>Actions</th>
            </thead>
            <tbody>
                @foreach($departments as $department)
                <tr>
                    <td>{{$department->deptcode}}</td>
                    <td>{{$department->deptname}}</td>
                    <td>
                        <a href="{{route('register.department.edit',$department->deptcode )}}"
                            class='btn btn-success btn-sm edit btn-flat'>
                            <i class='fa fa-edit'></i> Edit
                        </a>
                        <button class='btn btn-danger btn-sm delete btn-flat' data-bs-toggle="modal"
                            data-bs-target="#delete" data-deptcode="{{$department->deptcode}}"
                            data-deptname="{{$department->deptname}}">
                            <i class='fa fa-trash'></i> Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        {{ $departments->links('layouts.paginationlinks') }}
    </div>

    <!--delete student Modal -->
    <div class="modal fade" id="delete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="deleteLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Are you sure you want to delete?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Do you really want to delete <strong><span id="delete-department-name"></span></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm btn-flat"
                        data-bs-dismiss="modal">Cancel</button>
                    <form id="delete-department-form" method="POST"
                        action="{{ route('department.destroy', ['deptcode' => '__deptcode__']) }}">
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
            const deptcode = button.getAttribute('data-deptcode');
            const deptname = button.getAttribute('data-deptname');
            const deleteDepartmentForm = document.querySelector('#delete-department-form');
            const deleteDepartmentName = document.querySelector('#delete-department-name');
            const deleteDepartmentAction = deleteDepartmentForm.getAttribute('action').replace(
                '__deptcode__', deptcode);
            deleteDepartmentForm.setAttribute('action', deleteDepartmentAction);
            deleteDepartmentName.textContent = `${deptname}`;

        });
    });
    </script>

    @endsection