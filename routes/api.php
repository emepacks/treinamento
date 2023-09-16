<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ReqResLoggersController;
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
// Login cliente/company
Route::post('/login', [UserController::class, 'login']);
// Cadastrar cliente
Route::post('/signup', [UserController::class, 'signup']);
// Request Route
Route::get('/request', [ReqResLoggersController::class, 'request']);
// Response Route
Route::get('/response', [ReqResLoggersController::class, 'response']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Dados do cliente
    Route::get('/client', [UserController::class, 'getUser']);
    // Atualizar dados do cliente
    Route::match(['put', 'patch'],'/client', [UserController::class, 'update']);
    // Deletar cliente
    Route::delete('/client', [UserController::class, 'destroy']);
    // Listar empresas que o cliente faz parte
    Route::get('/client/companies', [UserController::class, 'showCompanies']);

    Route::middleware(['admin.access'])->group(
        function () {
            // Listar todas as empresas
            Route::get('/companies', [CompanyController::class, 'index']);
            // Listar uma empresa
            Route::get('/company', [CompanyController::class, 'show']);
            // Cadastrar empresa
            Route::post('/company', [CompanyController::class, 'store']);
            // Atualizar empresa
            Route::match(['put', 'patch'],'/company/{id}', [CompanyController::class, 'update']);
            // Deletar empresa
            Route::delete('/company/{id}', [CompanyController::class, 'destroy']);
            // Listar todos os clientes
            Route::get('/company/clients/{id}', [CompanyController::class, 'showClients']);
            // Adicionar cliente a empresa
            Route::post('/company/client/{id}', [CompanyController::class, 'addClient']);
        }
    );
});
