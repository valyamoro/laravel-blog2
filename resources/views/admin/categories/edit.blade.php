@extends('admin.layouts.default')

@section('title', $title)

@section('breadcrumbs')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Изменить категорию</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('categories.edit', $item) }}">Изменить
                                категорию</a></li>
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
                    @if(isset($errors))
                        @error('error')
                        <span class="text-red">{{ $message }}</span>
                        @enderror
                    @endif
                    <form action="{{ route('categories.update', $item) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('admin.categories.components.form_field', $item)
                        <button type="submit" class="btn btn-primary">Изменить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
