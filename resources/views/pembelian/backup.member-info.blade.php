@extends('layouts.app')

    @section('title', 'Informasi Member')

    @section('content')
    <div class="container mt-4">
        <h3 class="mb-4">Informasi Member</h3>
        
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row">
                    <!-- Daftar Produk -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Produk yang dipilih</h5>
                        @php
                            $selectedProducts = json_decode($products, true);
                        @endphp
                        @foreach($selectedProducts as $product)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="mb-0 fw-medium">{{ $product['name'] }}</p>
                                <small class="text-muted">Rp. {{ number_format($product['price'], 0, ',', '.') }} X {{ $product['quantity'] }}</small>
                            </div>
                            <div>
                                <p class="mb-0">Rp. {{ number_format($product['subtotal'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @endforeach
                        <hr class="my-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="font-weight-bold">Total</span>
                            <span class="mb-0">Rp. {{ number_format($total_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex mt-4 justify-content-between align-items-center">
                            <span class="font-weight-bold">Total Bayar</span>
                            <span class="mb-0">Rp. {{ number_format($total_bayar, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex mt-4 justify-content-between align-items-center">
                            <span class="font-weight-bold">Poin Yang Di Dapat</span>
                            <span class="mb-0">{{ $points }}</span>
                        </div>
                    </div>
    
                    <!-- Form -->
                    <div class="col-md-6">
                        <form action="{{ route('pembelian.pembayaran') }}" method="POST">
                            @csrf
                            <input type="hidden" name="products" value="{{ $products }}">
                            <input type="hidden" name="total_amount" value="{{ $total_amount }}">
                            <input type="hidden" name="total_bayar" value="{{ $total_bayar }}">
                            <input type="hidden" name="phone_number" value="{{ $phone_number }}">
                            <input type="hidden" name="member_type" value="member">
                            <input type="hidden" name="points_to_use" value="{{ $existingPoints }}">
    
                            <div class="form-group mb-3">
                                <label class="form-label">Nama Member (identitas)</label>
                                <input type="text" name="member_name" class="form-control" 
                                    value="{{ $memberName }}" {{ $memberName ? '' : 'required' }}>
                            </div>
    
                            <div class="form-group mb-3">
                                <label class="form-label">Poin</label>
                                <div class="p-2 rounded border bg-light">
                                    <span class="fw-medium">{{ $existingPoints }}</span>
                                </div>
                                
    
                                @if($isNewMember)
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" disabled>
                                    <label class="form-check-label text-danger">
                                        Poin tidak dapat digunakan pada pembelanjaan pertama
                                    </label>
                                </div>
                                @else
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="use_points">
                                    <label class="form-check-label">
                                        Gunakan poin
                                    </label>
                                </div>
                                @endif
                            </div>
    
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    Selanjutnya
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection