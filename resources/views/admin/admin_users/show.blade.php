@extends('admin.layouts.default')

@section('title', $title)

@section('breadcrumbs')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Профиль</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin-users.index') }}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('admin-users.show', $item) }}">Профиль</a></li>
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
                <h3 class="profile-username text-center">{{ $item->username }}</h3>

                <p class="text-muted text-center">Администратор</p>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Обо мне</h3>
            </div>
            <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i>Почта</strong>
                <p class="text-muted">
                    {{ $item->email }}
                </p>
                <strong><i class="fas fa-pen mr-1"></i>Статус</strong>
                <p class="text-muted">
                    @if($item->is_banned === true) Забанен @else Не забанен @endif
                </p>
            </div>
        </div>
    </div>
@endsection
