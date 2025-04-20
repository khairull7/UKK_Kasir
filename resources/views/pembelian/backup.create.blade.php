@extends('layouts.app')

@section('title', 'Tambah Penjualan')

@section('content')
    <div class="container mt-4 mb-5 pb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h2 font-weight-bold text-dark">Tambah Penjualan Baru</h2>
            <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <div class="">
            <form action="{{ route('pembelian.confirm') }}" method="POST" class="card-body" id="purchaseForm">
                @csrf
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 d-flex align-items-center ">
                                @if($product->img)
                                    <img src="{{ asset('storage/'.$product->img) }}" 
                                        alt="{{ $product->nama_produk }}" 
                                        class="img-fluid mb-3 rounded-lg mt-3"
                                        style="height: 150px; width: 150px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('img/no-image.png') }}"
                                        alt="No Image"
                                        class="img-fluid mb-3 rounded-lg mt-3"
                                        style="height: 150px; width: 150px; object-fit: cover;">
                                @endif
                                <div class="card-body text-center">
                                    <h5 class="card-title font-weight-bold">{{ $product->nama_produk }}</h5>
                                    <p class="text-muted">Stok: {{ $product->stok }}</p>
                                    <p class="font-weight-bold text-dark mb-3" data-price="{{ $product->harga }}">
                                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                                    </p>
                                    <div class="d-flex align-items-center justify-content-center mb-3">
                                        <button type="button" class="btn btn-outline-secondary minus-btn">-</button>
                                        <input type="number" 
                                               name="quantities[{{ $product->id }}]" 
                                               value="0" 
                                               min="0" 
                                               max="{{ $product->stok }}"
                                               class="form-control text-center mx-2 quantity-input" 
                                               style="width: 60px;" 
                                               readonly>
                                        <button type="button" class="btn btn-outline-secondary plus-btn">+</button>
                                    </div>
                                    <p class="text-muted">
                                        Sub Total: <strong class="subtotal text-dark">Rp 0</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </form>
        </div>
    </div>

    <!-- Fixed Button yang lebih sederhana -->
    <div class="fixed-bottom p-3 d-flex justify-content-end" style="margin-left: 250px; border-top: none;">
        <button type="submit" form="purchaseForm" class="btn btn-primary" style="width: 200px;">
            Selanjutnya
        </button>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const products = document.querySelectorAll('.card.h-100');
                
                products.forEach(product => {
                    const minusBtn = product.querySelector('.minus-btn');
                    const plusBtn = product.querySelector('.plus-btn');
                    const quantityInput = product.querySelector('.quantity-input');
                    const subtotalElement = product.querySelector('.subtotal');
                    const price = parseInt(product.querySelector('[data-price]').dataset.price);
                    
                    function updateSubtotal() {
                        const quantity = parseInt(quantityInput.value);
                        const subtotal = price * quantity;
                        subtotalElement.textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
                    }
                    
                    minusBtn.addEventListener('click', () => {
                        if (parseInt(quantityInput.value) > 0) {
                            quantityInput.value = parseInt(quantityInput.value) - 1;
                            updateSubtotal();
                        }
                    });
                    
                    plusBtn.addEventListener('click', () => {
                        if (parseInt(quantityInput.value) < parseInt(quantityInput.max)) {
                            quantityInput.value = parseInt(quantityInput.value) + 1;
                            updateSubtotal();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection