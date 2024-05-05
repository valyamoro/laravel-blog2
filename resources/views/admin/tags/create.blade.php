@extends('admin.layouts.default')

@section('title', $title)

@section('breadcrumbs')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Добавить тэг</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('tags.index') }}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('tags.create') }}">Добавить тэг</a></li>
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
                    <form action="{{ route('tags.store') }}" method="post">
                        @csrf
                        @include('admin.tags.components.form_field')
                        @include('admin.components.button_end', ['route' => 'tags.index'])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
