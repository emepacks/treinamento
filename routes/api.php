<?php

use App\Http\Controllers\CompanyController;
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

Route::post('/login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Dados do cliente
    Route::get('/client/{id}', [UserController::class, 'getUser']);
    // Atualizar dados do cliente
    Route::match(['put', 'patch'],'/client/{id}', [UserController::class, 'update']);
    // Deletar cliente
    Route::delete('/client/{id}', [UserController::class, 'destroy']);
    // Listar empresas que o cliente faz parte
    Route::get('/client/companies/{id}', [UserController::class, 'showCompanies']);

    Route::middleware(['admin.access'])->group(
        function () {
            // Cadastrar cliente
            Route::post('/signup', [UserController::class, 'signup']);
            // Listar todas as empresas
            Route::get('/companies', [CompanyController::class, 'index']);
            // Listar uma empresa
            Route::get('/companies/{id}', [CompanyController::class, 'show']);
            // Cadastrar empresa
            Route::post('/companies', [CompanyController::class, 'store']);
            // Atualizar empresa
            Route::put('/companies/{id}', [CompanyController::class, 'update']);
            // Deletar empresa
            Route::delete('/companies/{id}', [CompanyController::class, 'destroy']);
            // Listar todos os clientes
            Route::get('/company/clients/{id}', [UserController::class, 'showClients']);
            // Adicionar cliente a empresa
            Route::post('/company/clients/{id}', [UserController::class, 'addClient']);
        }

    );
});
