<?php

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

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', App\Http\Controllers\API\DashboardController::class);
    Route::put('profile', [App\Http\Controllers\API\ProfileController::class, 'update']);
    Route::get('profile', [App\Http\Controllers\API\ProfileController::class, 'show']);
    Route::get('user-permissions', [App\Http\Controllers\API\PermissionController::class, 'userPermissions']);
    Route::put('change-password', App\Http\Controllers\API\ChangePasswordController::class);

    Route::apiResource('tenants', App\Http\Controllers\API\TenantController::class)->except(['destroy']);;
    Route::delete('tenants', [App\Http\Controllers\API\TenantController::class, 'destroy']);

    Route::apiResource('payment-methods', App\Http\Controllers\API\PaymentMethodController::class)->except(['destroy']);
    Route::delete('payment-methods', [App\Http\Controllers\API\PaymentMethodController::class, 'destroy']);

    Route::apiResource('measurement-units', App\Http\Controllers\API\MeasurementUnitController::class)->except(['destroy']);
    Route::delete('measurement-units', [App\Http\Controllers\API\MeasurementUnitController::class, 'destroy']);

    Route::apiResource('ncms', App\Http\Controllers\API\NcmController::class)->only(['index', 'show']);
    Route::apiResource('states', App\Http\Controllers\API\StateController::class)->only(['index', 'show']);
    Route::apiResource('cities', App\Http\Controllers\API\CityController::class)->only(['index', 'show']);

    Route::apiResource('users', App\Http\Controllers\API\UserController::class)->except(['destroy']);
    Route::delete('users', [App\Http\Controllers\API\UserController::class, 'destroy']);

    Route::apiResource('roles', App\Http\Controllers\API\RoleController::class)->except(['destroy']);
    Route::delete('roles', [App\Http\Controllers\API\RoleController::class, 'destroy']);

    Route::apiResource('permissions', App\Http\Controllers\API\PermissionController::class)->only(['index', 'show', 'update']);
    Route::get('permissions-tree', [App\Http\Controllers\API\PermissionController::class, 'permissionsToTree']);

    Route::get('status', [App\Http\Controllers\API\StatusController::class, 'status']);
    Route::get('tenant-status', [App\Http\Controllers\API\StatusController::class, 'tenantStatus']);
});
