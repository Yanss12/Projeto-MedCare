<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PacienteController;
use App\Http\Controllers\Api\ProfissionalController;
use App\Http\Controllers\Api\AgendamentoController;
use App\Http\Controllers\Api\EvolucaoController;
use App\Http\Controllers\Api\AuthController;

// Rota pública pra tentar fazer login na API e devolver o Token
Route::post('/login', [AuthController::class, 'login']);

// Tudo que tá aqui dentro precisa do Token de autenticação (Bearer Token) pra funcionar
Route::middleware('auth:sanctum')->group(function () {
    
    // Rota que devolve os dados de quem tá logado agora
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Invalida o token e desloga
    Route::post('/logout', [AuthController::class, 'logout']);

    // Cria as 5 rotas essenciais (GET, POST, GET {id}, PUT, DELETE) pra cada recurso magicamente
    Route::apiResource('pacientes', PacienteController::class);
    Route::apiResource('profissionais', ProfissionalController::class);
    Route::apiResource('agendamentos', AgendamentoController::class);
    Route::apiResource('evolucoes', EvolucaoController::class);
});
