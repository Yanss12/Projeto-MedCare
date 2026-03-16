<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    // Campos que permitimos preencher diretamente
    protected $fillable = [
        'user_id',
        'message',
        'is_read', // Marca se o usuário já leu ou não a notificação
    ];

    // Método rápido pra adicionar uma notificação nova pro usuário
    public static function addNotification($userId, $message)
    {
        // Cria a notificação de fato
        self::create([
            'user_id' => $userId,
            'message' => $message,
            'is_read' => false,
        ]);

        // Busca todas as notificações do usuário, das mais recentes pras mais velhas
        $notifications = self::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Limita a 10 notificações pra não encher o banco de lixo (apaga as mais velhas)
        if ($notifications->count() > 10) {
            $idsToDelete = $notifications->slice(10)->pluck('id');
            self::whereIn('id', $idsToDelete)->delete();
        }
    }
}
