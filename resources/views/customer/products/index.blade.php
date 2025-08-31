@extends('layouts.app')

@section('sidebar')
<nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('customer.dashboard') }}">🏠 Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('customer.products.index') }}">🛍️ Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('customer.orders.index') }}">📋 My Orders</a>
            </li>
        </ul>
        <div class="px-3 mt-4">
            <form method="POST" action="{{ route('customer.logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm w-100">Logout</button>
            </form>
        </div>
    </div>
</nav>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Browse Products</h1>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <form method="GET" class="row g-3">
            <div class="col-md-5">
                <input type="text" class="form-control" name="search"
                       placeholder="Search products..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @if(isset($categories))
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('customer.products.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="row">
    @if(isset($products) && $products->count() > 0)
        @foreach($products as $product)
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card h-100">
                <img src="{{ asset('storage/' . $product->image) }}"
                     class="card-img-top" alt="{{ $product->name }}"
                     style="height: 200px; object-fit: cover;"
                     onerror="this.src='https://via.placeholder.com/300x200'">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">{{ Str::limit($product->description, 80) }}</p>
                    <p class="card-text">
                        <strong class="text-success">${{ number_format($product->price, 2) }}</strong><br>
                        <small class="text-muted">Category: {{ $product->category }}</small><br>
                        <small class="text-muted">Stock: {{ $product->stock }} available</small>
                    </p>
                    <div class="mt-auto">
                        <a href="{{ route('customer.products.show', $product) }}"
                           class="btn btn-primary btn-sm me-2">View Details</a>
                        @if($product->stock > 0)
                            <a href="{{ route('customer.orders.create', ['product_id' => $product->id]) }}"
                               class="btn btn-success btn-sm">Order Now</a>
                        @else
                            <button class="btn btn-secondary btn-sm" disabled>Out of Stock</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="col-12">
            <div class="text-center py-5">
                <h4 class="text-muted">No products found</h4>
                <p class="text-muted">Try adjusting your search criteria.</p>
            </div>
        </div>
    @endif
</div>

@if(isset($products) && $products->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
@endif
@endsection
