@extends('layouts.app')
@section('title', 'Daftar Produk')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Daftar Produk</h1>
    <div>
        @if (auth()->user()->role === 'admin')
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Produk
            </a>
            <a href="{{ route('admin.products.export') }}" class="btn btn-success shadow-sm">
                <i class="fas fa-file-excel fa-sm text-white-50 mr-1"></i> Export Produk
            </a>
        @endif
    </div>
</div>


@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Gambar</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        @if (auth()->user()->role === 'admin')
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $product->name }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     style="width: 70px; height: 70px; object-fit: cover;"
                                     class="rounded shadow-sm">
                            </td>
                            <td class="text-primary font-weight-bold">
                                Rp{{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td>
                                <span class="badge badge-{{ $product->stock > 10 ? 'primary' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            @if (auth()->user()->role === 'admin')
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#updateStockModal{{ $product->id }}">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>

                        {{-- Modal Update Stok --}}
                        <div class="modal fade" id="updateStockModal{{ $product->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-box"></i> Update Stok - {{ $product->name }}
                                        </h5>
                                        <button type="button" class="close text-white" data-dismiss="modal">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('admin.products.updateStock', $product->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Nama Produk</label>
                                                <input type="text" class="form-control-plaintext font-weight-bold" value="{{ $product->name }}" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label>Stok Baru</label>
                                                <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" min="0" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                <i class="fas fa-times"></i> Batal
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
