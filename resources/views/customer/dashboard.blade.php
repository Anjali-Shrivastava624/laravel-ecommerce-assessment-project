@extends('layouts.app')

@section('sidebar')
<nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('customer.dashboard') }}">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('customer.products.index') }}">Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('customer.orders.index') }}">My Orders</a>
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
<h1 class="h2">Customer Dashboard</h1>
<div class="alert alert-info">
    Welcome {{ auth()->user()->name }}! Your dashboard is loading properly.
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5>Quick Actions</h5>
                <a href="{{ route('customer.products.index') }}" class="btn btn-primary">Browse Products</a>
                <a href="{{ route('customer.orders.index') }}" class="btn btn-secondary">View Orders</a>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
if (typeof window.Echo !== 'undefined') {
    console.log('Echo is loaded, setting up listeners...');

    window.Echo.private('user.{{ auth()->id() }}')
        .listen('.order.status.updated', (e) => {
            console.log('Order status updated:', e);

            if (Notification.permission === 'granted') {
                new Notification('Order Status Updated', {
                    body: `Order #${e.order_id} status changed to ${e.new_status}`,
                    icon: '/favicon.ico'
                });
            }

            location.reload();
        });
} else {
    console.log('Echo is not loaded');
}
</script>
@endsection
