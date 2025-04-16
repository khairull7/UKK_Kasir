@extends('layouts.app')
@section('title', 'Edit Produk')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Produk</h1>
</div>

{{-- Alert jika ada error --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i> Terdapat beberapa kesalahan:
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label font-weight-bold">Nama Produk</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $product->name }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="image" class="form-label font-weight-bold">Gambar Produk</label>
                    <input type="file" class="form-control" name="image" id="image">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="img-thumbnail mt-2" width="150">
                    @endif
                </div>

                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label font-weight-bold">Harga</label>
                    <input type="number" class="form-control" name="price" id="price" value="{{ $product->price }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="stock" class="form-label font-weight-bold">Stok</label>
                    <input type="number" class="form-control" name="stock" id="stock" value="{{ $product->stock }}" disabled>
                </div>
            </div>

            <div class="text-right">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
