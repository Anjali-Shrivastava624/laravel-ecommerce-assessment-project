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
    </div>
</nav>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Bulk Import Products</h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back to Products</a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>CSV File Upload</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6>CSV Format Requirements:</h6>
                    <ul class="mb-0">
                        <li>Header row: <code>name,description,price,category,stock,image</code></li>
                        <li>Maximum file size: 10MB</li>
                        <li>Supported formats: CSV, Excel (.xlsx)</li>
                        <li>Image field can be empty (will use default image)</li>
                        <li>Can handle up to 100,000 products</li>
                    </ul>
                </div>

                <form method="POST" action="{{ route('admin.products.import.process') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="csv_file" class="form-label">Select CSV/Excel File</label>
                        <input type="file" class="form-control @error('csv_file') is-invalid @enderror"
                               id="csv_file" name="csv_file" accept=".csv,.xlsx,.xls" required>
                        @error('csv_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">
                            📤 Start Import Process
                        </button>
                    </div>
                </form>

                <hr>

                <h6>Sample CSV Format:</h6>
                <div class="bg-light p-3 rounded">
                    <small>
                        <code>
                        name,description,price,category,stock,image<br>
                        "iPhone 14 Pro","Latest iPhone with A16 Bionic chip",999.99,"Electronics",50,"iphone-14-pro.jpg"<br>
                        "MacBook Air M2","Ultra-thin laptop with M2 chip",1199.99,"Electronics",30,""
                        </code>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Import Process</h5>
            </div>
            <div class="card-body">
                <p><strong>How it works:</strong></p>
                <ol>
                    <li>Upload your CSV/Excel file</li>
                    <li>File is validated for format</li>
                    <li>Products are processed in background</li>
                    <li>Large files are chunked for performance</li>
                    <li>Invalid rows are logged and skipped</li>
                </ol>

                <div class="alert alert-warning">
                    <small><strong>Note:</strong> For large files (10k+ products), processing may take several minutes. You can continue using the system while import runs in background.</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
