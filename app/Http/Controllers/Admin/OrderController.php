<?php


namespace App\Http\Controllers\Admin;

use App\Events\OrderStatusUpdated;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,shipped,delivered',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        broadcast(new OrderStatusUpdated($order->load('user'), $oldStatus));

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }
}
