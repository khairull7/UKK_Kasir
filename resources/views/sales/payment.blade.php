@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body p-5">
            <div class="d-flex justify-content-between mb-5">
                <div>
                    <h1 class="h3 mb-4">Pembayaran</h1>
                    <div class="btn-group">
                        <button class="btn btn-primary" onclick="window.print()">Unduh</button>
                        <a href="{{ route('petugas.sales.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
                <div class="text-right">
                    <h5 class="text-muted">Invoice - #{{ $sale->id }}</h5>
                    <p class="text-muted">{{ now()->format('d F Y') }}</p>
                </div>
            </div>

            <div class="table-responsive mb-5">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Quantity</th>
                            <th class="text-right">Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($selectedProducts as $product)
                        <tr>
                            <td>{{ $product['name'] }}</td>
                            <td>Rp. {{ number_format($product['price'], 0, ',', '.') }}</td>
                            <td>{{ $product['quantity'] }}</td>
                            <td class="text-right">Rp. {{ number_format($product['subtotal'], 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="bg-light p-3 rounded">
                        <h6 class="mb-2">POIN DIGUNAKAN</h6>
                        <h3 class="mb-0">0</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-light p-3 rounded">
                        <h6 class="mb-2">KASIR</h6>
                        <h3 class="mb-0">{{ Auth::user()->name }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-light p-3 rounded">
                        <h6 class="mb-2">KEMBALIAN</h6>
                        <h3 class="mb-0">Rp. {{ number_format($totalReturn ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>

            <div class="bg-dark text-white p-4 rounded mt-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="mb-0">TOTAL</h6>
                    </div>
                    <div class="col-auto">
                        <h3 class="mb-0">Rp. {{ number_format($total, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn-group, .navbar, .sidebar {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endsection