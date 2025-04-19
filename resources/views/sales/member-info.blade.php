@extends('layouts.app')

@section('title', 'Konfirmasi Member')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Konfirmasi Member</h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header font-weight-bold">Detail Produk</div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($selectedProducts as $product)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $product['name'] }}</strong><br>
                                    <small>{{ $product['quantity'] }} x Rp {{ number_format($product['price'], 0, ',', '.') }}</small>
                                </div>
                                <div>
                                    Rp {{ number_format($product['price'] * $product['quantity'], 0, ',', '.') }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <hr>
                    <div class="text-right font-weight-bold">
                        Total: Rp {{ number_format($totalBelanja, 0, ',', '.') }}
                    </div>
                    <div class="text-right font-weight-bold">
                        Total Bayar: Rp {{ number_format($totalBayar, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('petugas.sales.store') }}">
                        @csrf

                        @foreach($selectedProducts as $product)
                            <input type="hidden" name="items[{{ $product['id'] }}][quantity]" value="{{ $product['quantity'] }}">
                        @endforeach

                        <input type="hidden" name="total_price" value="{{ $totalBelanja }}">
                        <input type="hidden" name="member_type" value="member">
                        <input type="hidden" name="phone_number" value="{{ $customer->phone_number }}">
                        <input type="hidden" name="is_new_member" value="0">
                        <input type="hidden" name="earned_poin" value="{{ $earnedPoin }}">
                        <input type="hidden" name="payment_amount" value="{{ $totalBayar }}">

                        {{-- Form --}}
                        <div class="form-group">
                            <label>Nama Customer</label>
                            <input type="text" name="customer_name" class="form-control" value="{{ $customer->name }}">
                        </div>

                        <div class="form-group">
                            <label>Poin Saat Ini</label>
                            <input type="text" class="form-control" value="{{ number_format($customer->poin) }} Poin" readonly>
                        </div>

                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="usePoinCheckbox" name="use_poin" value="1">
                            <label class="form-check-label" for="usePoinCheckbox">Gunakan poin untuk potongan</label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Proses Pembayaran</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
