@extends('admin.layouts.default')

@section('title', $title)

@section('breadcrumbs')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Изменить тэг</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('tags.index') }}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('tags.edit', $item) }}">Изменить тэг</a></li>
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
                    <form action="{{ route('tags.update', $item) }}" method="post">
                        @csrf
                        @method('PUT')
                        @include('admin.tags.components.form_field', $item)
                        <button type="submit" class="btn btn-primary">Изменить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
