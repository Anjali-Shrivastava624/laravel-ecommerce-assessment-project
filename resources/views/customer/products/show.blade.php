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
    </div>
</nav>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Product Details</h1>
    <a href="{{ route('customer.products.index') }}" class="btn btn-secondary">Back to Products</a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <img src="{{ asset('storage/' . $product->image) }}"
                 class="card-img-top" alt="{{ $product->name }}"
                 style="height: 400px; object-fit: cover;"
                 onerror="this.src='https://via.placeholder.com/400x400'">
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">{{ $product->name }}</h2>
                <p class="card-text">{{ $product->description }}</p>

                <div class="row mb-3">
                    <div class="col-sm-6">
                        <strong>Category:</strong>
                        <span class="badge bg-info">{{ $product->category }}</span>
                    </div>
                    <div class="col-sm-6">
                        <strong>Stock:</strong>
                        <span class="badge {{ $product->stock > 10 ? 'bg-success' : ($product->stock > 0 ? 'bg-warning' : 'bg-danger') }}">
                            {{ $product->stock }} available
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <h3 class="text-success">${{ number_format($product->price, 2) }}</h3>
                </div>

                @if($product->stock > 0)
                    <form method="GET" action="{{ route('customer.orders.create') }}">
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity:</label>
                            <select name="quantity" id="quantity" class="form-select" style="width: 100px;">
                                @for($i = 1; $i <= min(10, $product->stock); $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                🛒 Order Now
                            </button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning">
                        <strong>Out of Stock</strong><br>
                        This product is currently unavailable.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
