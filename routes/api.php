<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UniversityController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Set Throttling group for APIs execute 10 times in evry 1 minute.
 */
Route::middleware('throttle:10,1')->group(function () {
    //Set route for calling 
    Route::post('/country', [UniversityController::class, 'getCountryByIp']);
    Route::post('/universities', [UniversityController::class, 'getUniversitiesByCountry']);
});
