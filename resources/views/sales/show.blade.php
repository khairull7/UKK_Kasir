@extends('layouts.app')

@section('title', 'Detail Penjualan')

@section('content')
<div class="container">
    <!-- Title and header section -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Penjualan</h1>
    </div>

    <!-- Card for Sale Details -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Penjualan</h6>
            <!-- Invoice and Date on the right side -->
            <div class="text-right">
                <h5 class="text-muted">Invoice - #{{ $sale->id }}</h5>
                <p class="text-muted">{{ now()->format('d F Y') }}</p>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Member Details -->
                <div class="col-md-6">
                    <p><strong>Status Member:</strong> {{ $sale->customer ? 'Member' : 'Non-Member' }}</p>
                    <p><strong>No. HP:</strong> {{ $sale->customer ? $sale->customer->no_telp : '-' }}</p>
                    <p><strong>Poin Member:</strong> {{ $sale->customer ? $sale->customer->points : '0' }}</p>
                    <p><strong>Bergabung Sejak:</strong> {{ $sale->customer ? $sale->customer->created_at->format('d M Y') : '-' }}</p>
                </div>

                <!-- Sale Date and Staff -->
                <div class="col-md-6">
                    <p><strong>Dibuat pada:</strong> {{ $sale->created_at->format('d M Y H:i:s') }}</p>
                    <p><strong>Oleh:</strong> {{ $sale->staff->role }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Card for Sale Items -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Pembelian</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->details as $detail)
                    <tr>
                        <td>{{ $detail->product->name }}</td>
                        <td>{{ $detail->amount }}</td> 
                        <td>Rp {{ number_format($detail->product->price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <th colspan="3" class="text-right">Total</th>
                        <th>Rp {{ number_format($sale->total_price, 0, ',', '.') }}</th>
                    </tr>
                        <th colspan="3" class="text-right">Total Bayar</th>
                        <th>Rp {{ number_format($sale->total_pay, 0, ',', '.') }}</th>
                    </tr>
                    </tr>
                        <th colspan="3" class="text-right">Total Kembalian</th>
                        <th>Rp {{ number_format($sale->total_return, 0, ',', '.') }}</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
