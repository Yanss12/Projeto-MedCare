<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evolucao;

class EvolucaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Evolucao::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $evolucao = Evolucao::create($request->all());
        return response()->json($evolucao, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $evolucao = Evolucao::findOrFail($id);
        return response()->json($evolucao);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $evolucao = Evolucao::findOrFail($id);
        $evolucao->update($request->all());
        return response()->json($evolucao);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $evolucao = Evolucao::findOrFail($id);
        $evolucao->delete();
        return response()->json(null, 204);
    }
}
