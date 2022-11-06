<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('publishedNote', [ApiController::class, 'store']);
Route::put('publishedNote', [ApiController::class, 'update']);
Route::delete('publishedNote', [ApiController::class, 'destroy']);
