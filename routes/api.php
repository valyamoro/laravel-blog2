<?php

use App\Http\Controllers\Admin\Api\UpdateCommentStatusController;
use Illuminate\Support\Facades\Route;

Route::patch('comment/status/{comment}', UpdateCommentStatusController::class);
