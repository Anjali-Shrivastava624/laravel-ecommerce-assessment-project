@extends('layouts.app')

@section('sidebar')
<nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.products.index') }}">📦 Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('admin.orders.index') }}">🛒 Orders</a>
            </li>
        </ul>
    </div>
</nav>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Orders Management</h1>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
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
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Order Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($orders) && $orders->count() > 0)
                @foreach($orders as $order)
                <tr>
                    <td><strong>#{{ $order->id }}</strong></td>
                    <td>{{ $order->user->name }}</td>
                    <td>${{ number_format($order->total_amount, 2) }}</td>
                    <td>
                        <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'shipped' ? 'info' : 'success') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-info">View</a>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-warning dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Status
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="pending">
                                            <button type="submit" class="dropdown-item">Mark as Pending</button>
                                        </form>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="shipped">
                                            <button type="submit" class="dropdown-item">Mark as Shipped</button>
                                        </form>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="delivered">
                                            <button type="submit" class="dropdown-item">Mark as Delivered</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="text-center">No orders found</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

@if(isset($orders) && $orders->hasPages())
    <div class="d-flex justify-content-center">
        {{ $orders->links() }}
    </div>
@endif
@endsection

@section('scripts')
<script>
    console.log('Admin orders page loaded');
</script>
@endsection
