<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VcardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:5,1');

Route::middleware('checklogin')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [VcardController::class, 'index'])->name('dashboard');
    Route::post('/vcards', [VcardController::class, 'create'])->name('vcards.store');
    Route::put('/vcards/{vcard}', [VcardController::class, 'update'])->name('vcards.update');
    Route::delete('/vcards/{vcard}', [VcardController::class, 'destroy'])->name('vcards.destroy');
});

Route::get('/{link_name}/vcf', [VcardController::class, 'downloadVcf'])->name('vcards.vcf');
Route::get('/{link_name}', [VcardController::class, 'show'])->name('vcards.public');
