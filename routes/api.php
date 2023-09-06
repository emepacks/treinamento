<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::post('/login',[UserController::class, 'login']);
Route::post('/signup',[UserController::class, 'signup']);

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/client/companies/{id}', [UserController::class, 'showCompanies']);
    Route::put('/client/{id}', [UserController::class, 'update']);
    Route::delete('/client/{id}', [UserController::class, 'destroy']);
});


