<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use App\Services\PushNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderStatusNotification implements ShouldQueue
{
    use InteractsWithQueue;

    protected $pushService;

    public function __construct(PushNotificationService $pushService)
    {
        $this->pushService = $pushService;
    }

    public function handle(OrderStatusUpdated $event)
    {
        $order = $event->order;
        $user = $order->user;

        $title = 'Order Status Updated';
        $body = "Your order #{$order->id} status has been updated to: " . ucfirst($order->status);
        $data = [
            'order_id' => $order->id,
            'status' => $order->status,
            'url' => route('customer.orders.show', $order->id),
        ];

        $this->pushService->sendNotification($user, $title, $body, $data);
    }
}
