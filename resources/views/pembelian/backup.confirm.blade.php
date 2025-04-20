@extends('layouts.app')

@section('title', 'Konfirmasi Penjualan')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Konfirmasi Penjualan</h3>
    
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <!-- Kolom Kiri - Informasi Produk -->
                <div class="col-md-6">
                    <h5 class="mb-3">Produk yang dipilih</h5>
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
                        <span class="fw-medium">Total</span>
                        <span class="fw-bold">Rp. {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <!-- Info Poin yang didapatkan -->
                    @if($points_earned > 0)
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="fw-medium">Poin yang didapatkan</span>
                        <span class="fw-bold">{{ number_format($points_earned, 0, ',', '.') }} Poin</span>
                    </div>
                    @endif
                </div>

                <!-- Kolom Kanan - Form Pembayaran -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label fw-medium">Member Status <span class="text-muted small">Dapat juga membuat member</span></label>
                        <select id="memberType" name="member_type" class=" form-control ">
                            <option value="non_member">Bukan Member</option>
                            <option value="member">Member</option>
                        </select>
                    </div>

                    <div id="memberFields" class="mb-3 d-none">
                        <label class="form-label">No Telepon</label>
                        <input type="text" name="phone_number" class="form-control">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Total Bayar</label>
                        <input type="text" name="total_bayar" id="total_bayar" class="form-control" 
                               placeholder="Rp. " oninput="validatePayment(this.value)">
                        <div id="payment_error" class="invalid-feedback d-none">Jumlah bayar kurang.</div>
                    </div>

                    <form action="{{ route('pembelian.member-info') }}" method="POST" id="payment_form">
                        @csrf
                        <input type="hidden" name="products" value="{{ json_encode($selectedProducts) }}">
                        <input type="hidden" name="total_amount" value="{{ $total }}">
                        <input type="hidden" name="member_type" id="hidden_member_type">
                        <input type="hidden" name="phone_number" id="hidden_phone_number">
                        <input type="hidden" name="total_bayar" id="hidden_total_bayar">
                        <div class="mt-4">
                            <button type="submit" id="submit_btn" class="btn btn-primary w-100">
                                Selanjutnya
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Update form action based on member type
    document.addEventListener('DOMContentLoaded', function() {
        const memberType = document.getElementById('memberType');
        updateFormAction(memberType.value);
    });

    document.getElementById('memberType').addEventListener('change', function() {
        const memberFields = document.getElementById('memberFields');
        memberFields.classList.toggle('d-none', this.value === 'non_member');
        updateFormAction(this.value);
    });

    function updateFormAction(memberType) {
        const paymentForm = document.getElementById('payment_form');
        if (memberType === 'non_member') {
            paymentForm.action = "{{ route('pembelian.pembayaran') }}";
        } else {
            paymentForm.action = "{{ route('pembelian.member-info') }}";
        }
    }

    function formatRupiah(angka) {
        const number_string = angka.toString().replace(/[^,\d]/g, '');
        const split = number_string.split(',');
        const sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        const ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            const separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return 'Rp. ' + rupiah;
    }

    function validatePayment(value) {
        const cleanValue = parseInt(value.replace(/[^\d]/g, '')) || 0;
        const totalAmount = {{ $total }};
        const errorElement = document.getElementById('payment_error');
        const submitButton = document.getElementById('submit_btn');
        const inputElement = document.getElementById('total_bayar');
        
        inputElement.value = formatRupiah(value);
        document.getElementById('hidden_total_bayar').value = cleanValue;

        if (cleanValue < totalAmount) {
            errorElement.classList.remove('d-none');
            inputElement.classList.add('is-invalid');
            submitButton.disabled = true;
        } else {
            errorElement.classList.add('d-none');
            inputElement.classList.remove('is-invalid');
            submitButton.disabled = false;
        }
    }

    document.getElementById('payment_form').addEventListener('submit', function(e) {
        e.preventDefault();
        const memberType = document.getElementById('memberType').value;
        const phoneNumber = document.querySelector('input[name="phone_number"]')?.value;
        const totalBayar = document.getElementById('total_bayar').value;

        document.getElementById('hidden_member_type').value = memberType;
        document.getElementById('hidden_phone_number').value = phoneNumber || '';
        document.getElementById('hidden_total_bayar').value = totalBayar.replace(/[^\d]/g, '');

        if (memberType === 'member' && !phoneNumber) {
            alert('Mohon isi nomor telepon untuk member');
            return;
        }

        if (!totalBayar) {
            alert('Mohon isi total bayar');
            return;
        }

        this.submit();
    });
</script>
@endpush
@endsection
