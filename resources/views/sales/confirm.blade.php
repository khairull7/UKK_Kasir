@extends('layouts.app')

@section('title', 'Konfirmasi Penjualan')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Konfirmasi Penjualan</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form id="payment_form" method="POST" action="{{ route('petugas.sales.store') }}">
                @csrf
                @foreach($selectedProducts as $product)
                    <input type="hidden" name="items[{{ $product['id'] }}][quantity]" value="{{ $product['quantity'] }}">
                @endforeach
                <input type="hidden" name="total_amount" value="{{ $total }}">
                <input type="hidden" id="hidden_member_type" name="member_type" value="non_member">
                <input type="hidden" id="hidden_phone_number" name="phone_number" value="">
                <input type="hidden" id="hidden_total_bayar" name="payment_amount" value="">

                {{-- Tambahan yang kamu maksud --}}
                <input type="hidden" id="hidden_is_new_member" name="is_new_member" value="0">
                <input type="hidden" id="hidden_used_poin" name="used_poin" value="0">
                <input type="hidden" name="total_poin" value="{{ $customer->poin ?? 0 }}">
                
                <div class="row">
                    <!-- Product Information -->
                    <div class="col-md-6">
                        <h5 class="font-weight-bold mb-3">Produk yang dipilih</h5>
                        @foreach($selectedProducts as $product)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <p class="font-weight-bold mb-0">{{ $product['name'] }}</p>
                                    <small class="text-muted">
                                        Rp. {{ number_format($product['price'], 0, ',', '.') }} × {{ $product['quantity'] }}
                                    </small>
                                </div>
                                <div class="text-right">
                                    <p class="mb-0">Rp. {{ number_format($product['subtotal'], 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach

                        <div class="border-top pt-3 mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="font-weight-bold">Total</h5>
                                <h5 class="font-weight-bold">Rp. {{ number_format($total, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Member Status</label>
                            <select id="memberType" class="form-control">
                                <option value="non_member">Non Member</option>
                                <option value="member">Member</option>
                            </select>
                        </div>

                        <div id="memberFields" class="form-group d-none">
                    <label>No Telepon</label>
                    <input type="text" name="no_telp" id="no_telp" class="form-control" oninput="searchMember()">
                    <div id="member_name" class="mt-2"></div> <!-- Tempat menampilkan nama member -->
                </div>


                        <div class="form-group">
                            <label>Total Bayar</label>
                            <input type="text" name="total_pay" id="total_bayar" 
                                   class="form-control" placeholder="Rp. "
                                   oninput="validatePayment(this.value)">
                            <small id="payment_error" class="text-danger d-none">
                                Jumlah bayar kurang dari total pembelian
                            </small>
                        </div>

                        <button type="submit" id="submit_btn" 
                                class="btn btn-primary btn-block">
                            Proses Pembayaran
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('memberType').addEventListener('change', function() {
        const memberFields = document.getElementById('memberFields');
        memberFields.classList.toggle('d-none', this.value === 'non_member');
    });

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
        
        document.getElementById('total_bayar').value = formatRupiah(value);
        document.getElementById('hidden_total_bayar').value = cleanValue;

        if (cleanValue < totalAmount) {
            errorElement.classList.remove('d-none');
            submitButton.disabled = true;
            submitButton.classList.add('opacity-50');
        } else {
            errorElement.classList.add('d-none');
            submitButton.disabled = false;
            submitButton.classList.remove('opacity-50');
        }
    }

    document.getElementById('payment_form').addEventListener('submit', function(e) {
        e.preventDefault();
        const memberType = document.getElementById('memberType').value;
        const phoneNumber = document.querySelector('input[name="no_telp"]')?.value;
        const totalBayar = document.getElementById('total_bayar').value;

        document.getElementById('hidden_member_type').value = memberType;
        document.getElementById('hidden_phone_number').value = phoneNumber || '';
        document.getElementById('hidden_total_bayar').value = totalBayar.replace(/[^\d]/g, '');
        document.getElementById('hidden_is_new_member').value = (memberType === 'member') ? 1 : 0;
        document.getElementById('hidden_used_poin').value = 0;


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