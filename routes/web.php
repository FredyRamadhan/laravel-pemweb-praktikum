<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatatanController;
use Illuminate\Support\Facades\Route;

Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
Route::post('/masuk', [AuthController::class, 'login']);
Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
Route::post('/daftar', [AuthController::class, 'register']);
Route::post('/keluar', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [CatatanController::class, 'read'])->name('home');
    Route::post('/create', [CatatanController::class, 'create']);
    Route::get('/edit/{id}', [CatatanController::class, 'edit']);
    Route::post('/update/{id}', [CatatanController::class, 'update']);
    Route::post('/delete/{id}', [CatatanController::class, 'delete']);
});


