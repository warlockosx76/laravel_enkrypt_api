<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;

// Регистрация
Route::post('/register', [RegisteredUserController::class, 'store']);

// Логин
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Получение текущего авторизованного пользователя (по токену)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// (Опционально) logout
Route::middleware('auth:sanctum')->post('/logout', [AuthenticatedSessionController::class, 'destroy']);
