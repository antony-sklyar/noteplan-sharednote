<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('publish', [ApiController::class, 'publish']);
Route::delete('publish', [ApiController::class, 'unpublish']);
