@extends('layouts.app')
@section('title', 'Penjualan')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Penjualan</h1>

    <div class="row">
<<<<<<< HEAD
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
=======
   
>>>>>>> a9114d1e3e5ccc5852d3f516047877fe62f192b5
    </div>

    <form id="salesForm" method="POST" action="#" class="mt-4">
        @csrf
        <div id="selected-products"></div>
        <button type="submit" class="btn btn-primary px-5">Selanjutnya</button>
    </form>
</div>


@endsection