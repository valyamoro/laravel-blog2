@extends('admin.layouts.default')

@section('title', $title)

@section('breadcrumbs')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Категория</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin-users.index') }}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('categories.show', $item) }}">Страница
                                категории</a></li>
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
                    <img class="profile-user-img" src="{{ asset('/storage/' . $item->thumbnail) }}" alt="Изображение категории" style="border: none">
                </div>
                <h3 class="profile-username text-center">{{ $item->name }}</h3>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Информация</h3>
            </div>
            <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i>Описание</strong>
                <p class="text-muted">
                    {{ $item->content }}
                </p>
                <strong><i class="fas fa-pen mr-1"></i>Статус</strong>
                <p class="text-muted">
                    @if($item->is_active === true)
                        Активен
                    @else
                        Не активен
                    @endif
                </p>
            </div>
        </div>
    </div>
@endsection
