@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard Petugas</h1>
        </div>

        <!-- Greeting Message -->
        <div class="alert alert-primary text-center">
            <strong>Selamat Datang, {{ ucfirst(auth()->user()->role) }}!</strong>
        </div>

        <!-- Total Sales Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary text-white text-center">
                <h6 class="m-0 font-weight-bold">Informasi Penjualan</h6>
            </div>
            <div class="card-body text-center">
                <h5 class="card-title">Total Penjualan Hari Ini</h5>
                <h2 class="mb-0 font-weight-bold text-success">{{ $totalSales  }}</h2>
                <p class="mt-2 text-muted">Jumlah total penjualan yang terjadi hari ini.</p>
                <p class="small text-muted">Terakhir diperbarui: {{ $lastUpdated  }}</p>
            </div>
        </div>
    </div>
@endsection