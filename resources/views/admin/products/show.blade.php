@extends('layouts.app')

@section('sidebar')
<nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('admin.products.index') }}">📦 Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.orders.index') }}">🛒 Orders</a>
            </li>
        </ul>
        <div class="px-3 mt-4">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm w-100">Logout</button>
            </form>
        </div>
    </div>
</nav>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Product Details</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">Edit Product</a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-secondary">Back to Products</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Product Image</h5>
            </div>
            <div class="card-body text-center">
                <img src="{{ asset('storage/' . $product->image) }}"
                     class="img-fluid rounded" alt="{{ $product->name }}"
                     style="max-height: 400px; width: auto;"
                     onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Product Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="30%">Product ID:</th>
                        <td><strong>#{{ $product->id }}</strong></td>
                    </tr>
                    <tr>
                        <th>Name:</th>
                        <td><strong>{{ $product->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Description:</th>
                        <td>{{ $product->description }}</td>
                    </tr>
                    <tr>
                        <th>Category:</th>
                        <td>
                            <span class="badge bg-info">{{ $product->category }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Price:</th>
                        <td>
                            <h4 class="text-success">${{ number_format($product->price, 2) }}</h4>
                        </td>
                    </tr>
                    <tr>
                        <th>Stock:</th>
                        <td>
                            <span class="badge {{ $product->stock > 10 ? 'bg-success' : ($product->stock > 0 ? 'bg-warning' : 'bg-danger') }}">
                                {{ $product->stock }} units available
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td>{{ $product->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated:</th>
                        <td>{{ $product->updated_at->format('M d, Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Product Actions</h5>
            </div>
            <div class="card-body">
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                        ✏️ Edit Product
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-success">
                        ➕ Add New Product
                    </a>
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                            🗑️ Delete Product
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if($product->orderItems && $product->orderItems->count() > 0)
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Order History</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Order Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->orderItems as $orderItem)
                            <tr>
                                <td><a href="{{ route('admin.orders.show', $orderItem->order) }}">#{{ $orderItem->order->id }}</a></td>
                                <td>{{ $orderItem->order->user->name }}</td>
                                <td>{{ $orderItem->quantity }}</td>
                                <td>${{ number_format($orderItem->price, 2) }}</td>
                                <td>{{ $orderItem->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $orderItem->order->status === 'pending' ? 'warning' : ($orderItem->order->status === 'shipped' ? 'info' : 'success') }}">
                                        {{ ucfirst($orderItem->order->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
