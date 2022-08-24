<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DetectionController;
use App\Http\Controllers\ConfigController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::fallback(function () {
	return redirect('/detections');
});

Route::resource('detections', DetectionController::class);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/config', [ConfigController::class, 'show'])->name('show_config');
    Route::get('/config/edit', [ConfigController::class, 'edit'])->name('edit_config');
    Route::get('/config/form', [ConfigController::class, 'form'])->name('config_form');
    Route::post('/config/form', [ConfigController::class, 'write_config'])->name('update_config');
});
