@extends('layouts.admin')
@section('title','Modules')
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
        <a href="{{route('register.module.form')}}" class='btn btn-success col-1 mt-2'>
            <i class="fa fa-plus"></i> New
        </a>

        <div class="col-md-3 offset-2 mt-2">
            <h2>Modules</h2>
        </div>

        <div class="col-md-6">
            <form class="d-flex" action="{{ route('module.search') }}" method="GET">
                <input class="form-control me-2" name="modulecode" type="search" placeholder="Search"
                    aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>


    <div class="row mt-3">
        @if ($modules->isEmpty())
        <div class="alert alert-info">
            No records found. <a class="ms-2"
                href="{{route('register.module')}}">{{request()->routeIs('register.module')?'':'Go back'}}</a>
            <a class="ms-2" href="{{route('admin.dashboard')}}">Go To Dashboard</a>
        </div>
        @else
        <table id="example1" class="table table-striped table-hover">
            <thead>
                <th>Module Code</th>
                <th>Module Name</th>
                <th>Module Credit</th>
                <th>Actions</th>
            </thead>
            <tbody>
                @foreach($modules as $module)
                <tr>
                    <td>{{$module->modulecode}}</td>
                    <td>{{$module->modulename}}</td>
                    <td>{{$module->credit}}</td>
                    <td>
                        <a href="{{route('register.module.edit',$module->modulecode)}}"
                            class='btn btn-success btn-sm edit btn-flat'>
                            <i class='fa fa-edit'></i> Edit
                        </a>
                        <button class='btn btn-danger btn-sm delete btn-flat' data-bs-toggle="modal"
                            data-bs-target="#delete" data-modulecode="{{$module->modulecode}}"
                            data-modulename="{{$module->modulename}}">
                            <i class='fa fa-trash'></i> Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        {{ $modules->links('layouts.paginationlinks') }}
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
                    <p>Do you really want to delete <strong><span id="delete-module-name"></span></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm btn-flat"
                        data-bs-dismiss="modal">Cancel</button>
                    <form id="delete-module-form" method="POST"
                        action="{{ route('module.destroy', ['modulecode' => '__modulecode__']) }}">
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
            const modulecode = button.getAttribute('data-modulecode');
            const modulename = button.getAttribute('data-modulename');
            const deleteModuleForm = document.querySelector('#delete-module-form');
            const deleteModuleName = document.querySelector('#delete-module-name');
            const deleteModuleAction = deleteModuleForm.getAttribute('action').replace(
                '__modulecode__', modulecode);
            deleteModuleForm.setAttribute('action', deleteModuleAction);
            deleteModuleName.textContent = `${modulename}`;

        });
    });
    </script>

    @endsection