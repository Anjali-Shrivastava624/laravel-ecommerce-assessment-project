<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use App\Models\Product;
use App\Jobs\ImportProductsJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

        $products = $query->paginate(20);
        $categories = Product::distinct()->pluck('category');

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $imagePath = 'default-product.jpg';
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'stock' => $request->stock,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'stock' => $request->stock,
        ];

        if ($request->hasFile('image')) {

            if ($product->image !== 'default-product.jpg') {
                Storage::disk('public')->delete($product->image);
            }
            $updateData['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($updateData);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        if ($product->image !== 'default-product.jpg') {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    public function bulkImport()
    {
        return view('admin.products.import');
    }

    public function processBulkImport(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt,xlsx',
        ]);

        $file = $request->file('csv_file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('imports', $fileName, 'local');

        ImportProductsJob::dispatch(storage_path('app/' . $filePath));

        return redirect()->route('admin.products.index')
            ->with('success', 'Products import started! This may take a few minutes for large files.');
    }
}
