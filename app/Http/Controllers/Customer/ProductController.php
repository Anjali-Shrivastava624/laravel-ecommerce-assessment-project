<?php
namespace App\Http\Controllers\Customer;

use Illuminate\Routing\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('search')) {
            $query->search($request->search);
        }

        if ($request->has('category') && $request->category != '') {
            $query->category($request->category);
        }

        $products = $query->paginate(12);
        $categories = Product::distinct()->pluck('category');

        return view('customer.products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        return view('customer.products.show', compact('product'));
    }
}
