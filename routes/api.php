<?php

use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\StatsController;
use Illuminate\Support\Facades\Route;

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);
Route::post('/posts', [PostController::class, 'store']);
Route::put('/posts/{id}', [PostController::class, 'update']);
Route::delete('/posts/{id}', [PostController::class, 'destroy']);
Route::put('/posts/{id}/tayang', [PostController::class, 'tayang']);

Route::get('/clients', [ClientController::class, 'index']);
Route::get('/clients/{id}', [ClientController::class, 'show']);

Route::get('/campaigns', [CampaignController::class, 'index']);
Route::get('/campaigns/{id}', [CampaignController::class, 'show']);
Route::post('/campaigns', [CampaignController::class, 'store']);

Route::get('/stats', [StatsController::class, 'index']);
