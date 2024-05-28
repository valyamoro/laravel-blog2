<?php

use App\Http\Controllers\Admin\Api\UpdateAdminUserStatusController;
use App\Http\Controllers\Admin\Api\UpdateTagStatusController;
use App\Http\Controllers\Admin\Api\UpdateCategoryStatusController;
use App\Http\Controllers\Admin\Api\UpdateArticleStatusController;
use App\Http\Controllers\Admin\Api\UpdateCommentStatusController;
use App\Http\Controllers\Admin\Api\UpdateUserStatusController;
use Illuminate\Support\Facades\Route;

Route::patch('adminUser/status/{adminUser}', UpdateAdminUserStatusController::class);
Route::patch('tag/status/{tag}', UpdateTagStatusController::class);
Route::patch('category/status/{category}', UpdateCategoryStatusController::class);
Route::patch('article/status/{article}', UpdateArticleStatusController::class);
Route::patch('comment/status/{comment}', UpdateCommentStatusController::class);
Route::patch('user/status/{user}', UpdateUserStatusController::class);
