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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // New Endpoint for "All Data"
    Route::get('/data', function () {
        return response()->json([
            'message' => 'This is the secured data from the API.',
            'medicines' => \App\Models\Medicine::take(10)->get(),
            'users' => \App\Models\User::all(),
            'stats' => [
                'total_orders' => \App\Models\Order::count(),
                'total_sales' => \App\Models\Invoice::count(),
            ]
        ]);
    });
});
