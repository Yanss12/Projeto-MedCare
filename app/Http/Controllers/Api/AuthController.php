<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Função responsável por fazer o login do usuário na API
    public function login(Request $request)
    {
        // Primeiro a gente valida se mandaram o e-mail e a senha certinhos
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Busca o usuário no banco pelo e-mail
        $user = User::where('email', $request->email)->first();

        // Se não achou usuário ou a senha não bate, barra o login e avisa
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciais inválidas.'
            ], 401);
        }

        // Tudo certo! Cria um token de acesso pro cara poder usar a API
        $token = $user->createToken('auth_token')->plainTextToken;

        // Retorna o token e os dados do usuário pra quem chamou
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer', // Tipo do token padrão
            'user' => $user
        ]);
    }

    // Função pra derrubar (invalidar) a sessão do usuário
    public function logout(Request $request)
    {
        // Pega o token atual que ele tá usando e apaga do banco
        $request->user()->currentAccessToken()->delete();

        // Retorna só uma mensagem de sucesso
        return response()->json([
            'message' => 'Logout realizado com sucesso.'
        ]);
    }
}
