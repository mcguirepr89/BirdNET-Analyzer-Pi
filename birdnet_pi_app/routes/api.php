<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Detection;



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

Route::get('/detections', function () {
    //return Detection::latest()->get();
    return Detection::latest()->filter(
                    request(['search'])
                )->withQueryString();
});

Route::get('/config', function () {
    return Configuration::latest()->get();
});
