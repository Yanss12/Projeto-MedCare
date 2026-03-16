<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    // Funcao responsavel por fazer o login do usuario na API
    public function login(Request $request)
    {
        // Valida se mandaram o e-mail e a senha certinhos
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Chave unica para rate limiting: email + IP de quem esta tentando logar
        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();
        $limiter = app(RateLimiter::class);

        // Bloqueia apos 5 tentativas falhas por minuto
        if ($limiter->tooManyAttempts($throttleKey, 5)) {
            $seconds = $limiter->availableIn($throttleKey);
            return response()->json([
                'message' => "Muitas tentativas. Tente novamente em {$seconds} segundos.",
            ], 429); // HTTP 429 = Too Many Requests
        }

        // Busca o usuario no banco pelo e-mail
        $user = User::where('email', $request->email)->first();

        // Se nao achou usuario ou a senha nao bate, barra o login
        if (!$user || !Hash::check($request->password, $user->password)) {
            // Incrementa o contador de tentativas falhas (expira em 60s)
            $limiter->hit($throttleKey, 60);

            return response()->json([
                'message' => 'Credenciais inválidas.',
            ], 401);
        }

        // Login OK: limpa o contador de tentativas e gera o token
        $limiter->clear($throttleKey);

        // Gera o token com nome descritivo (dispositivo + data)
        $deviceName = $request->input('device_name', 'api_client');
        $token = $user->createToken($deviceName . '_' . now()->format('Ymd'))->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ]);
    }

    // Funcao para invalidar (derrubar) a sessao do usuario
    public function logout(Request $request)
    {
        // Apaga o token atual que ele esta usando
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso.',
        ]);
    }
}

