@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pembayaran</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body p-4">
            {{-- Header with invoice info --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    @if(isset($member) && $member)
                    <div class="alert alert-info mb-0 py-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-tag mr-3"></i>
                            <div>
                                <h6 class="mb-1">Member: {{ $member->phone_number }}</h6>
                                <p class="mb-0 small">
                                    Member sejak: {{ \Carbon\Carbon::parse($member->member_since)->translatedFormat('d F Y') }} | 
                                    Poin: {{ number_format($member->points, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="text-right">
                    <h5 class="mb-1 font-weight-bold">INVOICE #{{ $invoice_number }}</h5>
                    <p class="text-muted mb-0 small">
                        <i class="far fa-calendar-alt mr-1"></i>
                        {{ date('d F Y') }}
                    </p>
                </div>
            </div>

            {{-- Products table --}}
            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Produk</th>
                            <th width="15%" class="text-center">Harga</th>
                            <th width="10%" class="text-center">Qty</th>
                            <th width="20%" class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($selectedProducts as $product)
                        <tr>
                            <td>{{ $product['name'] }}</td>
                            <td class="text-center">Rp. {{ number_format($product['price'], 0, ',', '.') }}</td>
                            <td class="text-center">{{ $product['quantity'] }}</td>
                            <td class="text-right">Rp. {{ number_format($product['subtotal'], 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">Total Bayar</th>
                            <th class="text-right">Rp. {{ number_format($total_bayar, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Payment summary --}}
            <div class="row">
                <div class="col-md-8">
                    <div class="bg-light p-3 rounded mb-3">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <h6 class="text-muted small mb-1">Poin Digunakan</h6>
                                <p class="font-weight-bold">{{ $points_used ?? 0 }}</p>
                            </div>
                            <!-- <div class="col-md-4 mb-2">
                                <h6 class="text-muted small mb-1">Total Bayar</h6>
                                <p class="font-weight-bold">Rp. {{ number_format($total_bayar, 0, ',', '.') }}</p>
                            </div> -->
                            <div class="col-md-4 mb-3">
                                <h6 class="text-muted small mb-1">Kasir</h6>
                                <p class="font-weight-bold">{{ $pembelian->dibuat_oleh }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h6 class="text-muted small mb-1">Kembalian</h6>
                                <p class="font-weight-bold">Rp. {{ number_format($kembalian, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-dark text-white p-3 rounded">
                        <h6 class="text-white-50 small mb-2">TOTAL PEMBELIAN</h6>
                        @if($discount_from_points > 0)
                            <p class="text-muted small mb-0"><del>Rp. {{ number_format($total, 0, ',', '.') }}</del></p>
                        @endif
                        <h3 class="font-weight-bold mb-0">Rp. {{ number_format($final_total, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>

            {{-- Action buttons --}}
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('pembelian.index') }}" class="btn btn-outline-secondary mr-2">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <a href="{{ route('pembelian.export_pdf', $pembelian->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-download"></i>Unduh PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection