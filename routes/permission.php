<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermissionController;

Route::group(['middleware' => ['token']], function () {
    /* Basicos */
    Route::get('permisos', [PermissionController::class, 'getPermissions'])->middleware(['permission:permission.view']);
    Route::get('permiso/{permiso_id}', [PermissionController::class, 'getPermission'])->middleware(['permission:permission.view']);

    Route::post('permiso/asignar/', [PermissionController::class, 'asignarPermisos'])->middleware(['permission:permission.asign']);
    Route::post('permiso/retirar/', [PermissionController::class, 'retirarPermiso'])->middleware(['permission:permission.asign']);

    Route::get('permiso/user/{user_id}', [PermissionController::class, 'viewPermissions'])->middleware(['permission:permission.view']);

    Route::get('roles', [PermissionController::class, 'getRoles'])->middleware(['permission:role.view']);
    Route::get('role/{rol_id}', [PermissionController::class, 'getRole'])->middleware(['permission:role.view']);

    Route::post('rol/asignar/', [PermissionController::class, 'asignarRol'])->middleware(['permission:role.asign']);
    Route::post('rol/retirar/', [PermissionController::class, 'retirarRol'])->middleware(['permission:role.asign']);

    Route::get('rol/user/{user_id}', [PermissionController::class, 'viewRoles'])->middleware(['permission:role.view']);
});