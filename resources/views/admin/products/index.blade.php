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
    <h1 class="h2">Products Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary">Add Product</a>
            <a href="{{ route('admin.products.import') }}" class="btn btn-sm btn-success">Bulk Import</a>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
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
                <button type="submit" class="btn btn-outline-secondary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($products) && $products->count() > 0)
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}" width="50" height="50"
                             class="img-thumbnail" onerror="this.src='https://via.placeholder.com/50'">
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category }}</td>
                    <td>${{ number_format($product->price, 2) }}</td>
                    <td>
                        <span class="badge {{ $product->stock > 10 ? 'bg-success' : 'bg-warning' }}">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-info">View</a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center">No products found</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

@if(isset($products) && $products->hasPages())
    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>
@endif
@endsection
