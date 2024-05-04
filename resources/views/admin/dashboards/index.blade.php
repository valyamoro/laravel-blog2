@extends('admin.layouts.default')

@section('title', $title)

@section('breadcrumbs')
    <div class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">{{ $title }}</a></li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        @include('admin.dashboards.components.small_box')
    </div>
    <div class="row">
        <section class="col-lg-7 connectedSortable">
        </section>
    </div>
@endsection
