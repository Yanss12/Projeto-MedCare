<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $agora       = Carbon::now('America/Sao_Paulo');
        $hoje        = $agora->format('Y-m-d');
        $iniciaMes   = $agora->copy()->startOfMonth()->format('Y-m-d');
        $terminaMes  = $agora->copy()->endOfMonth()->format('Y-m-d');
        $iniciaAno   = $agora->copy()->startOfYear()->format('Y-m-d');

        $totalPacientes     = \App\Models\Paciente::count();
        $totalProfissionais = \App\Models\Profissional::count();

        // Agendamentos de hoje (para os cards e consultas recentes)
        $agendamentosHoje = \App\Models\Agendamento::with(['paciente', 'profissional'])
            ->whereDate('data_hora', $hoje)
            ->orderBy('data_hora')
            ->get()
            ->map(fn($ag) => [
                'id'           => $ag->id,
                'paciente'     => $ag->paciente->nome ?? 'Desconhecido',
                'profissional' => $ag->profissional->nome ?? 'Desconhecido',
                'especialidade'=> $ag->profissional->especialidade ?? '',
                'horario'      => Carbon::parse($ag->data_hora)->setTimezone('America/Sao_Paulo')->format('H:i'),
                'status'       => $ag->status,
                'data'         => Carbon::parse($ag->data_hora)->setTimezone('America/Sao_Paulo')->format('Y-m-d'),
            ]);

        // Agendamentos do mês inteiro (para o calendário — quais dias têm consultas)
        $agendamentosDoMes = \App\Models\Agendamento::with(['paciente', 'profissional'])
            ->whereBetween(\Illuminate\Support\Facades\DB::raw("DATE(data_hora AT TIME ZONE 'America/Sao_Paulo')"), [$iniciaMes, $terminaMes])
            ->orderBy('data_hora')
            ->get()
            ->map(fn($ag) => [
                'id'           => $ag->id,
                'paciente'     => $ag->paciente->nome ?? 'Desconhecido',
                'profissional' => $ag->profissional->nome ?? 'Desconhecido',
                'especialidade'=> $ag->profissional->especialidade ?? '',
                'horario'      => Carbon::parse($ag->data_hora)->setTimezone('America/Sao_Paulo')->format('H:i'),
                'status'       => $ag->status,
                'data'         => Carbon::parse($ag->data_hora)->setTimezone('America/Sao_Paulo')->format('Y-m-d'),
            ]);

        // Contagens mensais do ano para o gráfico Anual
        $contagemMensal = [];
        for ($m = 1; $m <= 12; $m++) {
            $contagemMensal[$m] = \App\Models\Agendamento::whereYear('data_hora', $agora->year)
                ->whereMonth('data_hora', $m)
                ->count();
        }

        // Contagens semanais (últimas 7 semanas) para o gráfico Semanal
        $contagemSemanal = [];
        for ($w = 6; $w >= 0; $w--) {
            $inicio = $agora->copy()->subWeeks($w)->startOfWeek()->format('Y-m-d');
            $fim    = $agora->copy()->subWeeks($w)->endOfWeek()->format('Y-m-d');
            $contagemSemanal[] = [
                'label' => $agora->copy()->subWeeks($w)->startOfWeek()->format('d/m'),
                'count' => \App\Models\Agendamento::whereDate('data_hora', '>=', $inicio)
                    ->whereDate('data_hora', '<=', $fim)->count(),
            ];
        }

        return \Inertia\Inertia::render('Dashboard', [
            'totalPacientes'     => $totalPacientes,
            'totalProfissionais' => $totalProfissionais,
            'consultasHoje'      => $agendamentosHoje,
            'agendamentosDoMes'  => $agendamentosDoMes,
            'contagemMensal'     => $contagemMensal,
            'contagemSemanal'    => $contagemSemanal,
        ]);
    }
}
