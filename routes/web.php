<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Paciente;
use App\Models\Profissional;
use App\Models\Agendamento;
use App\Models\Notification;
use Carbon\Carbon;

// Rota de Login (Pública) - Serve a telinha pra fazer o login no sistema
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->intended('/agendamentos');
    }
    return Inertia::render('Login');
})->name('login');

Route::post('/login', function (Request $request) {
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

Route::post('/logout', function (Request $request) {
    Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Rotas Protegidas (Requer Autenticação) - Daqui em diante a pessoa PRECISA estar logada pra acessar
Route::middleware(['auth'])->group(function () {
    Route::get('/', function (Request $request) {
        $hoje_real = now()->format('Y-m-d');
        $data_selecionada = $request->query('date', $hoje_real);
        
        $totalPacientes = Paciente::count();
        $totalProfissionais = Profissional::count();
        $totalConsultasHoje = Agendamento::whereDate('data_hora', $hoje_real)->count();

        $agendamentosFiltro = Agendamento::with(['paciente', 'profissional'])
            ->whereDate('data_hora', $data_selecionada)
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
            'totalConsultasHoje' => $totalConsultasHoje,
            'consultasFiltro' => $agendamentosFiltro,
            'selectedDate'  => $data_selecionada
        ]);
    })->name('dashboard');

    Route::get('/pacientes', function () {
        $pacientes = Paciente::orderBy('nome')->get();
        return Inertia::render('Pacientes', [
            'pacientes' => $pacientes
        ]);
    })->name('pacientes');

    Route::get('/profissionais', function () {
        $profissionais = Profissional::orderBy('nome')->get();
        return Inertia::render('Profissionais', [
            'profissionais' => $profissionais
        ]);
    })->name('profissionais');

    Route::get('/agendamentos', function () {
        $agendamentos = Agendamento::all();
        $pacientes = Paciente::orderBy('nome')->get();
        $profissionais = Profissional::orderBy('nome')->get();

        return Inertia::render('Agendamentos', [
            'agendamentos' => $agendamentos,
            'pacientes' => $pacientes,
            'profissionais' => $profissionais
        ]);
    })->name('agendamentos');

    // === CRUD de Pacientes === //
    // Onde tudo de cadastrar, editar e excluir paciente acontece no backend
    Route::post('/pacientes', function (Request $request) {
        $data = $request->validate([
            'nome' => 'required|string',
            'cpf' => 'required|string',
            'telefone' => 'nullable|string',
            'endereco' => 'nullable|string',
            'data_nascimento' => 'nullable|date',
            'necessitatransporte' => 'boolean',
            'diagnostico' => 'nullable|string',
            'alergias' => 'nullable|array',
            'medicamentoscontrolados' => 'nullable|array',
            'idade' => 'nullable|integer'
        ]);
        Paciente::create($data);
        Notification::addNotification($request->user()->id, 'Paciente '.$data['nome'].' cadastrado.');
        return redirect()->back()->with('success', 'Paciente cadastrado.');
    });

    Route::put('/pacientes/{paciente}', function (Request $request, Paciente $paciente) {
        $data = $request->validate([
            'nome' => 'required|string',
            'cpf' => 'required|string',
            'telefone' => 'nullable|string',
            'endereco' => 'nullable|string',
            'data_nascimento' => 'nullable|date',
            'necessitatransporte' => 'boolean',
            'diagnostico' => 'nullable|string',
            'alergias' => 'nullable|array',
            'medicamentoscontrolados' => 'nullable|array',
            'idade' => 'nullable|integer'
        ]);
        $paciente->update($data);
        Notification::addNotification($request->user()->id, 'Paciente '.$data['nome'].' atualizado.');
        return redirect()->back()->with('success', 'Paciente atualizado.');
    });

    Route::delete('/pacientes/{paciente}', function (Request $request, Paciente $paciente) {
        $name = $paciente->nome;
        $paciente->delete();
        Notification::addNotification($request->user()->id, 'Paciente '.$name.' removido.');
        return redirect()->back()->with('success', 'Paciente excluído com sucesso.');
    });

    // CRUD de Profissionais
    Route::post('/profissionais', function (Request $request) {
        $data = $request->validate([
            'nome' => 'required|string',
            'especialidade' => 'required|string',
            'crm' => 'required|string',
            'registro_interno' => 'nullable|string',
            'telefone' => 'nullable|string',
            'email' => 'nullable|email',
            'horasvoluntarias' => 'nullable|numeric',
            'disponibilidade' => 'nullable|array',
            'horarios' => 'nullable|string',
            'status' => 'nullable|string',
        ]);
        Profissional::create($data);
        Notification::addNotification($request->user()->id, 'Profissional '.$data['nome'].' adicionado.');
        return redirect()->back()->with('success', 'Profissional cadastrado.');
    });

    Route::put('/profissionais/{profissional}', function (Request $request, Profissional $profissional) {
        $data = $request->validate([
            'nome' => 'required|string',
            'especialidade' => 'required|string',
            'crm' => 'required|string',
            'registro_interno' => 'nullable|string',
            'telefone' => 'nullable|string',
            'email' => 'nullable|email',
            'horasvoluntarias' => 'nullable|numeric',
            'disponibilidade' => 'nullable|array',
            'horarios' => 'nullable|string',
            'status' => 'nullable|string',
        ]);
        $profissional->update($data);
        Notification::addNotification($request->user()->id, 'Profissional '.$data['nome'].' editado.');
        return redirect()->back()->with('success', 'Profissional atualizado.');
    });

    Route::delete('/profissionais/{profissional}', function (Request $request, Profissional $profissional) {
        $name = $profissional->nome;
        $profissional->delete();
        Notification::addNotification($request->user()->id, 'Profissional '.$name.' removido.');
        return redirect()->back()->with('success', 'Profissional excluído com sucesso.');
    });

    // CRUD de Agendamentos pela Web
    Route::post('/agendamentos', function (Request $request) {
        $data = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'profissional_id' => 'required|exists:profissionais,id',
            'data_hora' => 'required|date',
            'status' => 'required|string',
        ]);
        Agendamento::create($data);
        
        $pacienteData = Paciente::find($data['paciente_id']);
        $nomePaciente = $pacienteData ? $pacienteData->nome : 'um paciente';
        Notification::addNotification($request->user()->id, 'Nova consulta agendada para '.$nomePaciente.'.');
        return redirect()->back()->with('success', 'Agendamento criado com sucesso.');
    });

    Route::put('/agendamentos/{agendamento}', function (Request $request, Agendamento $agendamento) {
        $data = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'profissional_id' => 'required|exists:profissionais,id',
            'data_hora' => 'required|date',
            'status' => 'required|string',
        ]);
        $agendamento->update($data);
        Notification::addNotification($request->user()->id, 'Status de agendamento alterado para '.$data['status'].'.');
        return redirect()->back()->with('success', 'Agendamento atualizado com sucesso.');
    });

    Route::delete('/agendamentos/{agendamento}', function (Request $request, Agendamento $agendamento) {
        $agendamento->delete();
        Notification::addNotification($request->user()->id, 'Agendamento cancelado/excluído.');
        return redirect()->back()->with('success', 'Agendamento excluído com sucesso.');
    });

    Route::get('/prontuarios', function () {
        $pacientes = Paciente::with(['evolucoes' => function($query) {
            $query->orderBy('data_registro', 'desc');
        }])->orderBy('nome')->get();
        
        $profissionais = Profissional::orderBy('nome')->get();

        return Inertia::render('Prontuarios', [
            'prontuarios' => $pacientes,
            'profissionais' => $profissionais
        ]);
    })->name('prontuarios');

    Route::post('/notifications/mark-read', function (Request $request) {
        Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        return redirect()->back();
    })->name('notifications.mark.read');
});

// A rota coringa NotFound (404) será gerenciada automaticamente no front via Vue, 
// ou usando renderização de erro custom do Laravel/Inertia (Não mexe aqui).
