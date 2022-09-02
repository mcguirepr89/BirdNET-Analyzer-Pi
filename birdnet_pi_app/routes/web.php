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

    Route::resource('config', ConfigController::class)->only([
        'index', 'store'
    ]);

});

// This logs DB queries to storage/logs/laravel.log	
// \DB::listen(function($sql) {
//     \Log::info($sql->sql);
//     \Log::info($sql->bindings);
//     \Log::info($sql->time);
// });