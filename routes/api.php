<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PacienteController;
use App\Http\Controllers\Api\ProfissionalController;
use App\Http\Controllers\Api\AgendamentoController;
use App\Http\Controllers\Api\EvolucaoController;
use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('pacientes', PacienteController::class);
    Route::apiResource('profissionais', ProfissionalController::class);
    Route::apiResource('agendamentos', AgendamentoController::class);
    Route::apiResource('evolucoes', EvolucaoController::class);
});
