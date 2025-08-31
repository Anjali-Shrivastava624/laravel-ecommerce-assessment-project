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
    </div>
</nav>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Place New Order</h1>
    <a href="{{ route('customer.products.index') }}" class="btn btn-secondary">Back to Products</a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Order Details</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('customer.orders.store') }}" id="orderForm">
                    @csrf

                    <div id="orderItems">
                        @if(isset($product))
                            <div class="order-item border rounded p-3 mb-3">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                             alt="{{ $product->name }}" width="80" height="80"
                                             class="img-thumbnail"
                                             onerror="this.src='https://via.placeholder.com/80'">
                                    </div>
                                    <div class="col-md-4">
                                        <h6>{{ $product->name }}</h6>
                                        <small class="text-muted">{{ $product->category }}</small>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>${{ number_format($product->price, 2) }}</strong>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="hidden" name="items[0][product_id]" value="{{ $product->id }}">
                                        <input type="number" name="items[0][quantity]"
                                               class="form-control quantity-input"
                                               value="{{ request('quantity', 1) }}"
                                               min="1" max="{{ $product->stock }}" required>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-item">Remove</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <button type="button" class="btn btn-outline-primary" id="addItem">
                            ➕ Add Another Product
                        </button>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Order Summary</h5>
                            <div id="orderSummary">
                                <p>Total: <strong id="totalAmount">$0.00</strong></p>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="submit" class="btn btn-success btn-lg">
                                🛒 Place Order
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Order Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Customer:</strong> {{ auth()->user()->name }}</p>
                <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                <hr>
                <small class="text-muted">
                    <strong>Note:</strong> After placing your order, you will receive real-time updates about your order status. You can track your order in the "My Orders" section.
                </small>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let itemIndex = {{ isset($product) ? 1 : 0 }};

function calculateTotal() {
    let total = 0;
    document.querySelectorAll('.order-item').forEach(item => {
        const price = parseFloat(item.querySelector('.price').textContent.replace(', ''));
        const quantity = parseInt(item.querySelector('.quantity-input').value);
        total += price * quantity;
    });
    document.getElementById('totalAmount').textContent = ' + total.toFixed(2);
}

calculateTotal();

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('quantity-input')) {
        calculateTotal();
    }
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item')) {
        e.target.closest('.order-item').remove();
        calculateTotal();
    }
});
</script>
@endsection
