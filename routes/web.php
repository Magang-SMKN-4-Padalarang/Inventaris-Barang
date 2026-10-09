<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('login', [AuthController::class, 'showLoginFom'])->name('login');
Route::post('/api/login', [AuthController::class, 'login']);

Route::post('/login', [
    AuthController::class,
    'login'
])->name('login.process');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::post('/logout', [
    AuthController::class,
    'logout'
])->name('logout');
