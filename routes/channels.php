<?php
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('admin-dashboard', function ($user) {
    return $user->role === 'admin' ? ['id' => $user->id, 'name' => $user->name] : null;
});

Broadcast::channel('admin-presence', function ($user) {
    if ($user->role === 'admin' || $user->role === 'customer') {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
        ];
    }
    return null;
});
