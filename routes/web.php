<?php

use App\Http\Controllers\NoteViewController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/** default welcome page */
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

/** default user dashboard */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

/** NotePlan published notes */
Route::get('/n/{note}', [NoteViewController::class, 'promptPassword'])->name('note-prompt');
Route::post('/n/{note}', [NoteViewController::class, 'sendPassword'])->name('note-password');
Route::get('/n/{note}/{password}', [NoteViewController::class, 'viewWithPassword'])->name('note-view');
