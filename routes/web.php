<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('Login', [AuthController::class, 'showLoginFom'])->name('login');
Route::post('/api/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');