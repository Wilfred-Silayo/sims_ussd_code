@extends('layouts.admin')
@section('title','Password')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                <div class="card-body">
                    <div class="max-w-xl">
                        @include('profile.partials.admin_update_password')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection