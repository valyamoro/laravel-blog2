@extends('admin.layouts.default')

@section('title', $title)

@section('breadcrumbs')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Комментарий: #{{ $item->id }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('comments.index') }}">Список комментариев</a></li>
                        <li class="breadcrumb-item active">Страница комментария</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <div class="col-md-3">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Информация</h3>
            </div>
            <div class="card-body">
                <strong>Автор</strong>
                <p class="text-muted">
                    <a href="{{ route('admin-users.show', $item->adminUser->id) }}">{{ $item->adminUser->username }}</a>
                </p>
                <strong>Статья</strong>
                <p class="text-muted">
                    <a href="{{ route('articles.show', $item->article->id) }}">{{ $item->article->title }}</a>
                </p>
                <form action="{{ route('comments.update', $item) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <strong>Статус</strong>
                    @include('admin.comments.components.form_field')
                    <br>
                    <button type="submit" class="btn btn-primary">Изменить</button>
                </form>
            </div>
        </div>
    </div>
@endsection
