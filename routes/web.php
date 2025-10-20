<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PayableController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/', [LoginController::class, 'create'])->name('login');
    Route::post('/', [LoginController::class, 'store']);

    Route::get('/login', fn () => redirect()->route('login'));
});

Route::middleware('auth')->group(function (): void {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::resource('payables', PayableController::class)->except(['show']);
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});
