@extends('layouts.app')
@section('title', 'Add Product')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Produk Baru</h1>
        <a href="{{ route('products.index') }}" class="d-none d-sm-inline-block btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Products
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" id="productForm">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_produk">Nama Produk</label>
                            <input type="text" class="form-control @error('nama_produk') is-invalid @enderror" 
                                id="nama_produk" name="nama_produk" value="{{ old('nama_produk') }}" 
                                required minlength="3" maxlength="255">
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
                                    id="harga" name="harga" value="{{ old('harga') }}" 
                                    placeholder="0" required>
                            </div>
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stok">Stock</label>
                            <input type="number" class="form-control @error('stok') is-invalid @enderror" 
                                id="stok" name="stok" value="{{ old('stok', 0) }}" 
                                min="0" required>
                            @error('stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="image">Gambar Produk</label>
                            <div class="custom-file">
                                <input type="file" class="form-control @error('img') is-invalid @enderror" 
                                id="img" name="img" accept="image/jpeg,image/png,image/jpg" required>                            
                                @error('img')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror                            
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle mr-2"></i> Tambah Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>

    document.getElementById('harga').addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^\d]/g, '');

        if (value.length > 0) {
            value = parseInt(value, 10).toLocaleString('id-ID');
        }
        
        e.target.value = value;
    });

    document.getElementById('productForm').addEventListener('submit', function(e) {
        const priceInput = document.getElementById('harga');
        if (priceInput.value) {
            priceInput.value = priceInput.value.replace(/\./g, '');
        }
    });

    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'Choose file...';
        e.target.nextElementSibling.textContent = fileName;
    });

    document.getElementById('productForm').addEventListener('submit', function(e) {
        const priceInput = document.getElementById('harga');
        const numericValue = priceInput.value.replace(/\./g, '');
        
        if (!numericValue || isNaN(numericValue) || numericValue <= 0) {
            e.preventDefault();
            alert('Please enter a valid price');
            priceInput.focus();
        }
    });
</script>
@endpush