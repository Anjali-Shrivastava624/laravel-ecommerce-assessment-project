<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|url',
            'keys' => 'required|array',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        $user = Auth::user();

        $user->update([
            'push_subscription' => json_encode([
                'endpoint' => $request->endpoint,
                'keys' => $request->keys,
            ])
        ]);

        return response()->json(['success' => true]);
    }

    public function unsubscribe()
    {
        $user = Auth::user();
        $user->update(['push_subscription' => null]);

        return response()->json(['success' => true]);
    }
}
