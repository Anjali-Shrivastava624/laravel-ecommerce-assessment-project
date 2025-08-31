@extends('layouts.app')

@section('sidebar')
<nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('customer.dashboard') }}">🏠 Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('customer.products.index') }}">🛍️ Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('customer.orders.index') }}">📋 My Orders</a>
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
    <h1 class="h2">My Orders</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('customer.orders.create') }}" class="btn btn-sm btn-success">Place New Order</a>
    </div>
</div>

@if(isset($orders) && $orders->count() > 0)
    @foreach($orders as $order)
    <div class="card mb-3">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>Order #{{ $order->id }}</strong>
                    <small class="text-muted">{{ $order->created_at->format('M d, Y H:i') }}</small>
                </div>
                <div>
                    <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'shipped' ? 'info' : 'success') }} me-2">
                        {{ ucfirst($order->status) }}
                    </span>
                    <strong class="text-success">${{ number_format($order->total_amount, 2) }}</strong>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($order->orderItems as $item)
                <div class="col-md-6 col-lg-4 mb-2">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('storage/' . $item->product->image) }}"
                             alt="{{ $item->product->name }}" width="60" height="60"
                             class="rounded me-3"
                             onerror="this.src='https://via.placeholder.com/60'">
                        <div>
                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                            <small class="text-muted">
                                Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}
                            </small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-3">
                <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">View Details</a>
            </div>
        </div>
    </div>
    @endforeach

    @if($orders->hasPages())
        <div class="d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    @endif
@else
    <div class="text-center py-5">
        <h4 class="text-muted">No orders found</h4>
        <p class="text-muted">You haven't placed any orders yet.</p>
        <a href="{{ route('customer.products.index') }}" class="btn btn-primary">Browse Products</a>
    </div>
@endif
@endsection

@section('scripts')
<script>
    console.log('Orders page loaded');
</script>
@endsection
