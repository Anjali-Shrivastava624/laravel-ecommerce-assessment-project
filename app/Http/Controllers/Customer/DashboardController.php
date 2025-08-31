<?php


namespace App\Http\Controllers\Customer;

use App\Models\Product;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $recentProducts = Product::latest()->take(8)->get();
        $orders = auth()->user()->orders()->latest()->take(5)->get();

        return view('customer.dashboard', compact('recentProducts', 'orders'));
    }
}
