<?php
namespace App\Services;

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    protected $webPush;

    public function __construct()
    {
        $publicKey = env('VAPID_PUBLIC_KEY');
        $privateKey = env('VAPID_PRIVATE_KEY');

        if ($publicKey && $privateKey) {
            $this->webPush = new WebPush([
                'VAPID' => [
                    'subject' => config('app.url'),
                    'publicKey' => $publicKey,
                    'privateKey' => $privateKey,
                ],
            ]);
        }
    }

    public function sendNotification($user, $title, $body, $data = [])
    {
        if (!$this->webPush || !$user->push_subscription) {
            return false;
        }

        try {
            $subscriptionData = json_decode($user->push_subscription, true);
            $subscription = Subscription::create($subscriptionData);

            $payload = json_encode([
                'title' => $title,
                'body' => $body,
                'data' => $data,
                'icon' => '/favicon.ico',
                'badge' => '/favicon.ico',
                'actions' => [
                    [
                        'action' => 'view',
                        'title' => 'View Order'
                    ]
                ]
            ]);

            $result = $this->webPush->sendOneNotification($subscription, $payload);

            Log::info('Push notification sent', [
                'user_id' => $user->id,
                'success' => $result->isSuccess(),
                'reason' => $result->getReason()
            ]);

            return $result->isSuccess();
        } catch (\Exception $e) {
            Log::error('Push notification failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
