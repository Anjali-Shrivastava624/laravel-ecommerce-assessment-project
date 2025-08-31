<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'online_users' => User::where('is_online', true)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
