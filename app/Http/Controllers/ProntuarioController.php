<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Profissional;
use App\Http\Controllers\Controller;

class ProntuarioController extends Controller
{
    /**
     * Display a listing of patient records (prontuarios) with their evolutions.
     */
    public function index()
    {
        $pacientes = Paciente::with(['evolucoes' => function($query) {
            $query->orderBy('data_registro', 'desc');
        }])->orderBy('nome')->get();

        $profissionais = Profissional::orderBy('nome')->get();

        return \Inertia\Inertia::render('Prontuarios', [
            'prontuarios'   => $pacientes,
            'profissionais' => $profissionais,
        ]);
    }
}
