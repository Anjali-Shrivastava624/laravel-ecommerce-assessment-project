@extends('layouts.app')

@section('sidebar')
<nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
    <div class="position-sticky pt-3">
        <div class="px-3 mb-3">
            <h6 class="text-muted">Welcome, {{ auth()->user()->name }}</h6>
        </div>

        <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted">Admin Panel</h6>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                    📊 Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.products.index') }}">
                    📦 Products
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.orders.index') }}">
                    🛒 Orders
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.products.import') }}">
                    📤 Bulk Import
                </a>
            </li>
        </ul>

        <hr>

        <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted">Online Users</h6>
        <div id="online-users" class="px-3">
            @if(isset($stats['online_users']))
                @foreach($stats['online_users'] as $user)
                    <div class="user-status mb-1" data-user-id="{{ $user->id }}">
                        <span class="online-indicator">🟢</span>
                        <small>{{ $user->name }} ({{ ucfirst($user->role) }})</small>
                    </div>
                @endforeach
            @endif
        </div>

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
    <h1 class="h2">Admin Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Products</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_products'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <span style="font-size: 2rem;">📦</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Orders</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_orders'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <span style="font-size: 2rem;">🛒</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Customers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_customers'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <span style="font-size: 2rem;">👥</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Online Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ isset($stats['online_users']) ? $stats['online_users']->count() : 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <span style="font-size: 2rem;">🟢</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                         src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjIwMCIgaGVpZ2h0PSIyMDAiIGZpbGw9IiNlZWUiLz4KPHRleHQgeD0iMTAwIiB5PSIxMDAiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+Q2hhcnQgUGxhY2Vob2xkZXI8L3RleHQ+Cjwvc3ZnPg=="
                         alt="Chart">
                </div>
                <p>Real-time analytics and charts will be displayed here.</p>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">➕ Add New Product</a>
                    <a href="{{ route('admin.products.import') }}" class="btn btn-success">📤 Bulk Import Products</a>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-info">📋 View All Orders</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    console.log('Admin dashboard loaded');
</script>
@endsection
