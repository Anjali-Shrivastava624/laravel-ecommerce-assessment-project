@extends('layouts.app')

@section('sidebar')
<nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.products.index') }}">Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('admin.orders.index') }}">Orders</a>
            </li>
        </ul>
    </div>
</nav>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Order Details #{{ $order->id }}</h1>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Back to Orders</a>
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
                        <strong>Customer:</strong> {{ $order->user->name }}
                    </div>
                    <div class="col-sm-6">
                        <strong>Email:</strong> {{ $order->user->email }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-6">
                        <strong>Status:</strong>
                        <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'shipped' ? 'info' : 'success') }}">
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
                <h5>Update Order Status</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="status" class="form-label">Order Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>
                                Shipped
                            </option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>
                                Delivered
                            </option>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>

                <div class="mt-3">
                    <small class="text-muted">
                        Note: Changing the order status will send a real-time notification to the customer.
                    </small>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5>Customer Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $order->user->name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email }}</p>
                <p><strong>Member Since:</strong> {{ $order->user->created_at->format('M Y') }}</p>
                <p><strong>Total Orders:</strong> {{ $order->user->orders->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
