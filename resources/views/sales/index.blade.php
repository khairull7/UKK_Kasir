@extends('layouts.app')
@section('title', 'Pembelian')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Daftar Pembelian</h1>
    @if(Auth::user()->role == 'petugas')

    <a href="{{ route('petugas.sales.create') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Add Penjualan
    </a>
    @endif
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Nama Pelanggan</th>
                        <th>Tanggal Penjualan</th>
                        <th>Total Harga</th>
                        <th>Dibuat Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $sale->customer ? $sale->customer_name : 'Non-Member' }}</td>
                        <td class="text-center">{{ $sale->created_at->format('Y-m-d') }}</td>
                        <td>Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                        <td>{{ $sale->staff->role ?? 'Role not assigned' }}</td>
                        <td class="text-center">
                            <a href="{{ route(Auth::user()->role . '.sales.show', $sale->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Lihat
                            </a>
                            <a href="{{ route(Auth::user()->role . '.sales.pdf', $sale->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-download"></i> Unduh Bukti
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection