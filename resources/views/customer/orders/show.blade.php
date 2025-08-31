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
    <h1 class="h2">Order Details #{{ $order->id }}</h1>
    <a href="{{ route('customer.orders.index') }}" class="btn btn-secondary">Back to Orders</a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Order Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <strong>Order ID:</strong> #{{ $order->id }}
                    </div>
                    <div class="col-sm-6">
                        <strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-6">
                        <strong>Status:</strong>
                        <span id="order-status" class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'shipped' ? 'info' : 'success') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="col-sm-6">
                        <strong>Total Amount:</strong>
                        <h4 class="text-success d-inline">${{ number_format($order->total_amount, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5>Order Items</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                             alt="{{ $item->product->name }}" width="50" height="50"
                                             class="rounded me-3"
                                             onerror="this.src='https://via.placeholder.com/50'">
                                        <div>
                                            <h6 class="mb-0">{{ $item->product->name }}</h6>
                                            <small class="text-muted">{{ $item->product->category }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td><strong>${{ number_format($item->price * $item->quantity, 2) }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-primary">
                                <th colspan="3">Total Amount</th>
                                <th>${{ number_format($order->total_amount, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Order Status</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'shipped' ? 'info' : 'success') }}"
                             role="progressbar"
                             style="width: {{ $order->status === 'pending' ? '33%' : ($order->status === 'shipped' ? '66%' : '100%') }}">
                            {{ ucfirst($order->status) }}
                        </div>
                    </div>
                </div>

                <div class="timeline">
                    <div class="timeline-item">
                        <span class="badge bg-success">✓</span>
                        <span class="ms-2">Order Placed</span>
                        <small class="text-muted d-block">{{ $order->created_at->format('M d, Y H:i') }}</small>
                    </div>

                    @if($order->status === 'shipped' || $order->status === 'delivered')
                    <div class="timeline-item mt-2">
                        <span class="badge bg-info">✓</span>
                        <span class="ms-2">Shipped</span>
                    </div>
                    @endif

                    @if($order->status === 'delivered')
                    <div class="timeline-item mt-2">
                        <span class="badge bg-success">✓</span>
                        <span class="ms-2">Delivered</span>
                    </div>
                    @endif
                </div>

                <div class="mt-3">
                    <small class="text-muted">
                        You will receive real-time notifications when your order status changes.
                    </small>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5>Need Help?</h5>
            </div>
            <div class="card-body">
                <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                <p><small class="text-muted">Contact support if you have any questions about your order.</small></p>

                <div class="d-grid">
                    <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-primary btn-sm">View All Orders</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="notification-area"></div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (Notification.permission === 'default') {
        Notification.requestPermission();
    }

    console.log('Order details page loaded for order #{{ $order->id }}');

    if (typeof window.Echo !== 'undefined') {
        window.Echo.private('user.{{ auth()->id() }}')
            .listen('.order.status.updated', (e) => {
                if (e.order_id == {{ $order->id }}) {
                    updateOrderStatus(e.new_status);
                    showNotification('Order Status Updated', `Your order status has been updated to: ${e.new_status}`);
                }
            });
    }
});

function updateOrderStatus(newStatus) {
    const statusBadge = document.getElementById('order-status');
    if (statusBadge) {
        statusBadge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
        statusBadge.className = `badge bg-${newStatus === 'pending' ? 'warning' : (newStatus === 'shipped' ? 'info' : 'success')}`;
    }

    const progressBar = document.querySelector('.progress-bar');
    if (progressBar) {
        const width = newStatus === 'pending' ? '33%' : (newStatus === 'shipped' ? '66%' : '100%');
        progressBar.style.width = width;
        progressBar.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
    }
}

function showNotification(title, body) {
    if (Notification.permission === 'granted') {
        new Notification(title, {
            body: body,
            icon: '/favicon.ico'
        });
    }

    const notificationArea = document.getElementById('notification-area');
    const alert = document.createElement('div');
    alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
    alert.style.zIndex = '9999';
    alert.innerHTML = `
        <strong>${title}</strong><br>
        ${body}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    notificationArea.appendChild(alert);

    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}
</script>
@endsection
