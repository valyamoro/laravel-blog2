@extends('admin.layouts.default')

@section('title', $title)

@section('breadcrumbs')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Изменить администратора</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin-users.index') }}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('admin-users.edit', $item) }}">Изменить администратора</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="card-header">
                    <form action="{{ route('admin-users.update', $item) }}" method="post">
                        @csrf
                        @method('PUT')
                        @include('admin.admin_users.components.form_field', $item)
                        <button type="submit" class="btn btn-primary">Изменить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
