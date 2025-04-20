@extends('layouts.app')
@section('title', 'Edit Product')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Product</h1>
        <a href="{{ route('products.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Products
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_produk">Nama Produk</label>
                            <input type="text" class="form-control @error('nama_produk') is-invalid @enderror" 
                                id="nama_produk" name="nama_produk" 
                                value="{{ old('nama_produk', $product->nama_produk) }}" required>
                            @error('nama_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control @error('harga') is-invalid @enderror" 
                                    id="harga" name="harga" 
                                    value="{{ old('harga', $product->harga) }}" required>
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stok">Stok</label>
                            <input type="number" class="form-control" 
                                id="stok" name="stok" 
                                value="{{ $product->stok }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="img">Gambar Produk</label>
                            <input type="file" class="form-control @error('img') is-invalid @enderror" 
                                id="img" name="img" accept="image/*">
                            @error('img')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary">Update Product</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const priceInput = document.getElementById('harga');
    
    priceInput.addEventListener('input', function(e) {
        let value = this.value.replace(/[^\d]/g, '');
        
        if (value.length > 10) {
            value = value.slice(0, 10);
        }
        
        if (value) {
            value = parseInt(value, 10).toLocaleString('id-ID');
            value = value.replace(/[^\d.,]/g, '').split(',')[0];
        }
        
        this.value = value;
    });

    priceInput.form.addEventListener('submit', function(e) {
        if (priceInput.value) {
            priceInput.value = priceInput.value.replace(/\./g, '');
        } else {
            priceInput.value = '0';
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        if (priceInput.value === '0') {
            priceInput.value = '';
        }
    });
</script>
@endpush