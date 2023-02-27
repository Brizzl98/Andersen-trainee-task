<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/users', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/reset-password', [PasswordController::class, 'resetPassword']);
Route::post('/reset-password-with-token', [PasswordController::class, 'updatePassword']);
Route::middleware(['auth:api'])->group(function () {
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::get('/users', [UserController::class, 'getUsers']);
    Route::get('/users/{id}', [UserController::class, 'getUserData']);
    Route::delete('users/{id}', [UserController::class, 'delete']);
});
