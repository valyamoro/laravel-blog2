@extends('admin.layouts.default')

@section('title', $title)

@section('breadcrumbs')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Статья: {{ $item->title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin-users.index') }}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('articles.show', $item) }}">Страница
                                статьи</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <div class="col-md-3">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    @include('admin.articles.components.image_profile', ['item' => $item])
                </div>
                <h3 class="profile-username text-center">{{ $item->name }}</h3>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Информация</h3>
            </div>
            <div class="card-body">
                <strong>Автор</strong>
                <p class="text-muted">
                    {{ $item->adminUser->username }}
                </p>
                <strong>Категория</strong>
                <p class="text-muted">
                    {{ $item->category->name }}
                </p>
                @if([] !== $item->tags->toArray())
                    <strong>Тэги</strong>
                    <p class="text-muted">
                        @foreach($item->tags as $tag)
                            {{ $tag->name }}
                        @endforeach
                    </p>
                @endif
                <strong>Статус</strong>
                <p class="text-muted">
                    @if($item->is_active === true)
                        Активен
                    @else
                        Не активен
                    @endif
                </p>
                <strong>Содержимое статьи</strong>
                <p class="text-muted">
                    {{ $item->content }}
                </p>
            </div>
        </div>
    </div>
@endsection
