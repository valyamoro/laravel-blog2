<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest:admin'])->prefix('/admin')->group(function() {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('admin.login.form');
    Route::post('/login', [LoginController::class, 'login'])->name('admin.login.handler');
});

Route::middleware(['admin.auth:admin', 'admin.banned:admin'])->group(function() {
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

    /** Resource */
    Route::resource('/admin-users', AdminUserController::class);
    Route::resource('/admin/tags', TagController::class);
    Route::resource('/admin/categories', CategoryController::class);
    Route::resource('/admin/articles', ArticleController::class);
    Route::resource('/admin/comments', CommentController::class);
    Route::resource('/admin/users', UserController::class);
});
