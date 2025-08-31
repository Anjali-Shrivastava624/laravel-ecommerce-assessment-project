<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\UserPresenceUpdated;
use Illuminate\Support\Facades\Log;

class UpdateUserPresence
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $wasOnline = $user->is_online;

            $user->setOnlineStatus(true);

            if (!$wasOnline) {
                try {
                    broadcast(new UserPresenceUpdated($user, true))->toOthers();
                } catch (\Exception $e) {
                    Log::warning('User presence broadcast failed: ' . $e->getMessage());
                }
            }
        }

        return $next($request);
    }
}
