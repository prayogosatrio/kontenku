<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Publik
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Wajib login
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('clients', ClientController::class);
    Route::resource('campaigns', CampaignController::class);

    // Harus di atas Route::resource('posts'), supaya "jadwal" tidak tertangkap sebagai {post}.
    Route::get('/posts/jadwal', [PostController::class, 'jadwal'])->name('posts.jadwal');
    Route::resource('posts', PostController::class);
    Route::put('/posts/{post}/tayang', [PostController::class, 'tayang'])->name('posts.tayang');

    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Khusus admin
    Route::middleware(['admin'])->group(function () {
        Route::resource('platforms', PlatformController::class)->except(['show']);
    });
});
