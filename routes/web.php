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
        $todosAgendamentos = \App\Models\Agendamento::with(['paciente', 'profissional'])
            ->orderBy('data_hora', 'asc')
            ->get()
            ->map(function ($ag) {
                $carbonDate = \Carbon\Carbon::parse($ag->data_hora);
                return [
                    'id' => $ag->id,
                    'paciente' => $ag->paciente->nome ?? 'Desconhecido',
                    'profissional' => $ag->profissional->nome ?? 'Desconhecido',
                    'especialidade' => $ag->profissional->especialidade ?? '',
                    'data' => $carbonDate->format('Y-m-d'),
                    'horario' => $carbonDate->format('H:i'),
                    'status' => $ag->status
                ];
            });

        return Inertia::render('Dashboard', [
            'totalPacientes' => $totalPacientes,
            'totalProfissionais' => $totalProfissionais,
            'todasConsultas' => $todosAgendamentos
        ]);
    })->name('dashboard');

    Route::get('/pacientes', function () {
        $pacientes = \App\Models\Paciente::orderBy('nome')->get();
        return Inertia::render('Pacientes', [
            'pacientes' => $pacientes
        ]);
    })->name('pacientes');

    Route::post('/pacientes', function (\Illuminate\Http\Request $request) {
        \App\Models\Paciente::create($request->all());
        return redirect()->back();
    });

    Route::put('/pacientes/{id}', function (\Illuminate\Http\Request $request, $id) {
        $paciente = \App\Models\Paciente::findOrFail($id);
        $paciente->update($request->all());
        return redirect()->back();
    });

    Route::delete('/pacientes/{id}', function ($id) {
        $paciente = \App\Models\Paciente::findOrFail($id);
        if ($paciente->agendamentos()->count() > 0 || $paciente->evolucoes()->count() > 0) {
            return back()->withErrors(['error' => 'Não é possível excluir: Este paciente possui histórico clínico.']);
        }
        $paciente->delete();
        return redirect()->back();
    });

    Route::get('/profissionais', function () {
        $profissionais = \App\Models\Profissional::orderBy('nome')->get();
        return Inertia::render('Profissionais', [
            'profissionais' => $profissionais
        ]);
    })->name('profissionais');

    Route::post('/profissionais', function (\Illuminate\Http\Request $request) {
        \App\Models\Profissional::create($request->all());
        return redirect()->back();
    });

    Route::put('/profissionais/{id}', function (\Illuminate\Http\Request $request, $id) {
        $profissional = \App\Models\Profissional::findOrFail($id);
        $profissional->update($request->all());
        return redirect()->back();
    });

    Route::delete('/profissionais/{id}', function ($id) {
        $profissional = \App\Models\Profissional::findOrFail($id);
        if ($profissional->agendamentos()->count() > 0 || $profissional->evolucoes()->count() > 0) {
            return back()->withErrors(['error' => 'Não é possível excluir: Este profissional possui histórico clínico.']);
        }
        $profissional->delete();
        return redirect()->back();
    });

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

    Route::post('/agendamentos', function (\Illuminate\Http\Request $request) {
        \App\Models\Agendamento::create($request->all());
        return redirect()->back();
    });

    Route::put('/agendamentos/{id}', function (\Illuminate\Http\Request $request, $id) {
        $agendamento = \App\Models\Agendamento::findOrFail($id);
        $agendamento->update($request->all());
        return redirect()->back();
    });

    Route::delete('/agendamentos/{id}', function ($id) {
        $agendamento = \App\Models\Agendamento::findOrFail($id);
        $agendamento->delete();
        return redirect()->back();
    });

    Route::post('/evolucoes', function (\Illuminate\Http\Request $request) {
        \App\Models\Evolucao::create($request->all());
        return redirect()->back();
    });

    Route::put('/evolucoes/{id}', function (\Illuminate\Http\Request $request, $id) {
        $evolucao = \App\Models\Evolucao::findOrFail($id);
        $evolucao->update($request->all());
        return redirect()->back();
    });

    Route::delete('/evolucoes/{id}', function ($id) {
        $evolucao = \App\Models\Evolucao::findOrFail($id);
        $evolucao->delete();
        return redirect()->back();
    });

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
