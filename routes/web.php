<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VcardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

Route::middleware('checklogin')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [VcardController::class, 'index'])->name('dashboard');
    Route::post('/vcards', [VcardController::class, 'create'])->name('vcards.store');
    Route::put('/vcards/{vcard}', [VcardController::class, 'update'])->name('vcards.update');

    Route::middleware('admin')->group(function () {
        Route::delete('/vcards/{vcard}', [VcardController::class, 'destroy'])->name('vcards.destroy');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

Route::get('/{link_name}/vcf', [VcardController::class, 'downloadVcf'])->name('vcards.vcf');
Route::get('/{link_name}', [VcardController::class, 'show'])->name('vcards.public');
