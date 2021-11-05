<?php

use App\Http\Controllers\BillsController;
use App\Http\Controllers\ConsumersController;
use App\Http\Controllers\UsersController;
use App\Models\User;
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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResources([
        'consumers' => ConsumersController::class,
        'consumers.bills' => BillsController::class
    ]);
    Route::get('/dues', [BillsController::class, 'dues']);
    Route::get('/logout', [UsersController::class, 'logout']);
});

Route::post('/login', [UsersController::class, 'login']);
Route::post('/register', [UsersController::class, 'register']);
