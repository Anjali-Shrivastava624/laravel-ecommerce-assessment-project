<?php
// app/Events/OrderStatusUpdated.php
namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $oldStatus;

    public function __construct(Order $order, $oldStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->order->user_id);
    }

    public function broadcastWith()
    {
        return [
            'order_id' => $this->order->id,
            'new_status' => $this->order->status,
            'old_status' => $this->oldStatus,
        ];
    }

    public function broadcastAs()
    {
        return 'order.status.updated';
    }
}
