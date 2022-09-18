<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Detection;
use App\Models\Config;
use App\Http\Controllers\DetectionController;

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

// Display API endpoints in the 'api.blade.php' view
Route::get('/', function() {
	$routes = Route::getRoutes()->get();
	foreach ($routes as $route)
	{
		if(str_starts_with($route->uri, 'api'))
	    {
			$api_routes[] = $route;
	    }
	}
	return view('api', [ 'api_routes' => $api_routes ]);
});


// Endpoints
Route::get('/detections', [DetectionController::class, 'index']);
Route::get('/detections/species', [DetectionController::class, 'species']);
Route::get('/detections/com_name/{detection:com_name}', [DetectionController::class, 'show_com']);
Route::get('/detections/sci_name/{detection:sci_name}', [DetectionController::class, 'show_sci']);

Route::get('/config', function () { return Config::latest()->get(); });
