<?php

namespace App\Http\Controllers\Customer;


use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->with('orderItems.product')->latest()->paginate(10);
        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('orderItems.product');
        return view('customer.orders.show', compact('order'));
    }

    public function create(Request $request)
    {
        $product = null;
        if ($request->has('product_id')) {
            $product = Product::find($request->product_id);
        }

        return view('customer.orders.create', compact('product'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $totalAmount = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);

                if (!$product->inStock($item['quantity'])) {
                    throw new \Exception("Product {$product->name} is out of stock or insufficient quantity.");
                }

                $itemTotal = $product->price * $item['quantity'];
                $totalAmount += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];
            }

            $order = Order::create([
                'user_id' => auth()->id(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            foreach ($orderItems as $index => $orderItem) {
                $orderItem['order_id'] = $order->id;
                OrderItem::create($orderItem);

                $product = Product::find($orderItem['product_id']);
                $product->reduceStock($orderItem['quantity']);
            }
        });

        return redirect()->route('customer.orders.index')
            ->with('success', 'Order placed successfully!');
    }
}
