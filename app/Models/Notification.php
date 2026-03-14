<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'message',
        'is_read',
    ];

    public static function addNotification($userId, $message)
    {
        // Add new notification
        self::create([
            'user_id' => $userId,
            'message' => $message,
            'is_read' => false,
        ]);

        // Keep only top 10 most recent for this user
        $notifications = self::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($notifications->count() > 10) {
            $idsToDelete = $notifications->slice(10)->pluck('id');
            self::whereIn('id', $idsToDelete)->delete();
        }
    }
}
