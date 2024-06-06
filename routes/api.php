<?php

use App\Http\Controllers\Admin\Api\UpdateStatusController;
use Illuminate\Support\Facades\Route;

Route::patch('status/{tableName}/{id}', UpdateStatusController::class);
