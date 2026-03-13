<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\ProfissionalController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\ProntuarioController;
use App\Http\Controllers\EvolucaoController;

// Rota de Login (Pública)
Route::get('/login', function () {
    return Inertia::render('Login');
})->name('login');

Route::post('/login', function (Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Illuminate\Support\Facades\Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/');
    }

    return back()->withErrors([
        'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
    ]);
});

Route::post('/logout', function (Illuminate\Http\Request $request) {
    Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Rotas Protegidas (Requer Autenticação)
Route::middleware(['auth'])->group(function () {
    
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Módulos
    Route::resource('pacientes', PacienteController::class);
    Route::resource('profissionais', ProfissionalController::class);
    Route::resource('agendamentos', AgendamentoController::class);
    Route::resource('prontuarios', ProntuarioController::class);
    Route::resource('evolucoes', EvolucaoController::class);

});
