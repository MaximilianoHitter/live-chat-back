<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentacionController;

Route::group(['middleware' => ['token']], function () {
    Route::post('docs', [DocumentacionController::class, 'routes'])->middleware(['permission:documentacion']);
    Route::get('hash', [DocumentacionController::class, 'obtener_hash'])->middleware(['permission:documentacion']);
});