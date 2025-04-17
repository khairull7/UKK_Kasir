@extends('layouts.app')
@section('title', 'Penjualan')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Penjualan</h1>

    <div class="row">
        @foreach ($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card shadow h-100">
                    <div class="card-body text-center">
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="img-fluid mb-3 rounded-lg"
                                 style="height: 150px; object-fit: contain;">
                        @else
                            <img src="{{ asset('img/no-image.png') }}"
                                 alt="No Image"
                                 class="img-fluid mb-3 rounded-lg"
                                 style="height: 150px; object-fit: contain;">
                        @endif

                        <h4 class="card-title">{{ $product->name }}</h4>
                        <p class="text-muted">Stok {{ $product->stock }}</p>
                        <h5 class="text-primary font-weight-bold">
                            Rp. {{ number_format($product->price, 0, ',', '.') }}
                        </h5>

                        <div class="d-flex justify-content-center align-items-center my-3">
                            <div class="input-group" style="width: 200px;">
                                <div class="input-group-prepend">
                                    <button type="button" class="btn btn-outline-primary btn-minus" 
                                        data-id="{{ $product->id }}"
                                        data-price="{{ $product->price }}"
                                        {{ $product->stock == 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>

                                <input type="number" id="qty-{{ $product->id }}" 
                                    class="form-control text-center" 
                                    value="0" 
                                    min="0" 
                                    max="{{ $product->stock }}"
                                    {{ $product->stock == 0 ? 'disabled' : '' }}
                                    readonly>

                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-primary btn-plus" 
                                        data-id="{{ $product->id }}" 
                                        data-price="{{ $product->price }}"
                                        data-stock="{{ $product->stock }}"
                                        {{ $product->stock == 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="text-muted mb-3">
                            Sub Total <span id="subtotal-{{ $product->id }}" class="font-weight-bold">Rp. 0</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <form id="salesForm" method="POST" action="{{ route('petugas.sales.confirm') }}" class="mt-4">
        @csrf
        <div id="selected-products"></div>
        <button type="submit" class="btn btn-primary px-5">Selanjutnya</button>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const formatCurrency = (number) => {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    };

    const updateSubtotal = (id, quantity, price) => {
        const qtyInput = document.getElementById(`qty-${id}`);
        const plusBtn = document.querySelector(`.btn-plus[data-id="${id}"]`);
        const minusBtn = document.querySelector(`.btn-minus[data-id="${id}"]`);
        const stock = parseInt(plusBtn.dataset.stock);

        // Update quantity
        qtyInput.value = quantity;

        // Calculate and update subtotal
        const subtotal = price * quantity;
        const subtotalEl = document.getElementById(`subtotal-${id}`);
        subtotalEl.textContent = `Rp. ${formatCurrency(subtotal)}`;

        // Handle hidden input for form
        if (quantity > 0) {
            let formInput = document.querySelector(`input[name="items[${id}][quantity]"]`);
            if (!formInput) {
                formInput = document.createElement('input');
                formInput.type = 'hidden';
                formInput.name = `items[${id}][quantity]`;
                document.getElementById('selected-products').appendChild(formInput);
            }
            formInput.value = quantity;
        } else {
            const formInput = document.querySelector(`input[name="items[${id}][quantity]"]`);
            if (formInput) formInput.remove();
        }

        // Update button states
        plusBtn.disabled = quantity >= stock;
        minusBtn.disabled = quantity <= 0;
    };

    // Plus button handler
    document.querySelectorAll('.btn-plus').forEach(btn => {
        btn.addEventListener('click', () => {
            if (btn.disabled) return;
            
            const id = btn.dataset.id;
            const stock = parseInt(btn.dataset.stock);
            const price = parseInt(btn.dataset.price);
            const currentQty = parseInt(document.getElementById(`qty-${id}`).value) || 0;

            if (currentQty < stock) {
                updateSubtotal(id, currentQty + 1, price);
            }
        });
    });

    // Minus button handler
    document.querySelectorAll('.btn-minus').forEach(btn => {
        btn.addEventListener('click', () => {
            if (btn.disabled) return;
            
            const id = btn.dataset.id;
            const price = parseInt(btn.dataset.price);
            const currentQty = parseInt(document.getElementById(`qty-${id}`).value) || 0;

            if (currentQty > 0) {
                updateSubtotal(id, currentQty - 1, price);
            }
        });
    });

    // Form validation on submit
    document.getElementById('salesForm').addEventListener('submit', function(e) {
        const selectedProducts = document.querySelectorAll('input[name^="items"]');
        if (selectedProducts.length === 0) {
            e.preventDefault();
            alert('Silahkan pilih minimal satu produk');
            return;
    }
        this.method = 'POST';
        this.action = "{{ route('petugas.sales.confirm') }}";
        return true;
    });
});
</script>
@endpush

@endsection