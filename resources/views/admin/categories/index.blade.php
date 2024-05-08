@extends('admin.layouts.default')

@section('title', $title)

@section('breadcrumbs')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Список категорий</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('categories.index') }}">Список категорий</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><a href="{{ route('categories.create') }}" type="button" class="btn btn-block btn-primary">Добавить</a></div>
                    <div class="card-tools">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="input-group align-items-center">
                            @if(isset($errors))
                                @error('q')
                                <span class="text-red">{{ $message }}</span>
                                @enderror
                            @endif
                            @if(request()->has('q'))
                                <a href="{{ route('categories.index') }}" class="btn btn-navbar input-group-prepend">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                            <form action="{{ route('categories.index') }}" method="get">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <label for="q"></label>
                                    <input id="q" type="text" name="q" value="{{ request('q') }}"
                                           class="form-control"
                                           placeholder="Search">
                                    <div class="input-group-append">
                                        <button class="btn btn-navbar" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div style="margin-left: 20px; margin-top: 10px">
                    @if (isset($paginator->isEmptyItems))
                        <p>По вашему запросу, ничего не найдено.</p>
                    @endif
                </div>
                <div class="card body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Дата</th>
                            <th>Изображение</th>
                            <th>Название</th>
                            <th>Количество статей</th>
                            <th>Просмотры</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($paginator as $value)
                            @include('admin.categories.components.tr_index', [$value])
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0">
                        {{ $paginator->links('vendor.pagination.bootstrap-4') }}
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
