<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    Route::get('/', function () {
        $hoje = now()->format('Y-m-d');
        
        $totalPacientes = \App\Models\Paciente::count();
        $totalProfissionais = \App\Models\Profissional::count();
        $agendamentosHoje = \App\Models\Agendamento::with(['paciente', 'profissional'])
            ->whereDate('data_hora', $hoje)
            ->orderBy('data_hora', 'asc')
            ->get()
            ->map(function ($ag) {
                return [
                    'id' => $ag->id,
                    'paciente' => $ag->paciente->nome ?? 'Desconhecido',
                    'profissional' => $ag->profissional->nome ?? 'Desconhecido',
                    'especialidade' => $ag->profissional->especialidade ?? '',
                    'horario' => \Carbon\Carbon::parse($ag->data_hora)->format('H:i'),
                    'status' => $ag->status
                ];
            });

        return Inertia::render('Dashboard', [
            'totalPacientes' => $totalPacientes,
            'totalProfissionais' => $totalProfissionais,
            'consultasHoje' => $agendamentosHoje
        ]);
    })->name('dashboard');

    Route::get('/pacientes', function () {
        $pacientes = \App\Models\Paciente::orderBy('nome')->get();
        return Inertia::render('Pacientes', [
            'pacientes' => $pacientes
        ]);
    })->name('pacientes');

    Route::get('/profissionais', function () {
        $profissionais = \App\Models\Profissional::orderBy('nome')->get();
        return Inertia::render('Profissionais', [
            'profissionais' => $profissionais
        ]);
    })->name('profissionais');

    Route::get('/agendamentos', function () {
        $agendamentos = \App\Models\Agendamento::all();
        $pacientes = \App\Models\Paciente::orderBy('nome')->get();
        $profissionais = \App\Models\Profissional::orderBy('nome')->get();

        return Inertia::render('Agendamentos', [
            'agendamentos' => $agendamentos,
            'pacientes' => $pacientes,
            'profissionais' => $profissionais
        ]);
    })->name('agendamentos');

    Route::get('/prontuarios', function () {
        $pacientes = \App\Models\Paciente::with(['evolucoes' => function($query) {
            $query->orderBy('data_registro', 'desc');
        }])->orderBy('nome')->get();
        
        $profissionais = \App\Models\Profissional::orderBy('nome')->get();

        return Inertia::render('Prontuarios', [
            'prontuarios' => $pacientes,
            'profissionais' => $profissionais
        ]);
    })->name('prontuarios');
});

// A rota coringa NotFound (404) será gerenciada automaticamente no front via Vue, 
// ou usando renderização de erro custom do Laravel/Inertia.
