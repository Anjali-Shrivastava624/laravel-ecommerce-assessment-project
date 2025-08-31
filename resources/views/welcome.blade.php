<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Multi-Auth E-commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center mt-5">
                <h1 class="display-4 mb-4">Laravel Multi-Auth E-commerce</h1>
                <p class="lead mb-5">Complete e-commerce application with admin and customer portals</p>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Admin Portal</h5>
                                <p class="card-text">Manage products, orders, and view analytics</p>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('admin.login') }}" class="btn btn-primary">Admin Login</a>
                                    <a href="{{ route('admin.register') }}" class="btn btn-outline-primary">Admin Register</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Customer Portal</h5>
                                <p class="card-text">Browse products and place orders</p>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('customer.login') }}" class="btn btn-success">Customer Login</a>
                                    <a href="{{ route('customer.register') }}" class="btn btn-outline-success">Customer Register</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
