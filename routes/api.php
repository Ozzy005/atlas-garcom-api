<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth'])->group(function () {
    Route::put('change-password', App\Http\Controllers\API\ChangePasswordController::class);

    Route::apiResource('users', App\Http\Controllers\API\UserController::class);
    Route::apiResource('roles', App\Http\Controllers\API\RoleController::class);
    Route::apiResource('permissions', App\Http\Controllers\API\PermissionController::class)->only(['index', 'show', 'update']);
    Route::get('permissions-tree', [App\Http\Controllers\API\PermissionController::class, 'permissionsToTree']);
});
