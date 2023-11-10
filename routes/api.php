<?php

use App\Http\Controllers\Api\PriceController;
use App\Http\Controllers\Api\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/subscription/store', [SubscriptionController::class, 'store'])->name('api.subscription.store');
Route::get('/price/history-period', [PriceController::class, 'getHistoryPeriod'])->name('api.price.historyPeriod');
