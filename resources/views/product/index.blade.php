@extends('layouts.app')
@section('title', 'Products')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Daftar Produk</h1>
    @if(Auth::user()->role == 'admin')
        <div class="d-flex flex-wrap align-items-center gap-3">
            <a href="{{ route('products.create') }}" class="btn btn-primary shadow-sm mr-2">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Produk
            </a>
            <a href="{{ route('products.export') }}" class="btn btn-success shadow-sm">
                <i class="fas fa-file-excel fa-sm text-white-50"></i> Export Excel
            </a>
        </div>
    @endif

</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div></div>
            <div class="d-flex align-items-center">
                <span class="mr-2">Tampilkan</span>
                <select id="per-page" class="form-control form-control-sm" style="width: auto;">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="75">70</option>
                </select>
                <span class="mx-2">entri</span>

                <span class="mr-2">Cari:</span>
                <input type="text" id="search" class="form-control form-control-sm" style="width: 200px;" placeholder="Cari produk...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Gambar Produk</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        @if(Auth::user()->role == 'admin')
                        <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody id="product-table-body">
                    @forelse($products as $product)
                    <tr class="text-center align-middle">
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">
                            @if($product->img)
                                <img src="{{ asset('storage/'.$product->img) }}" 
                                    alt="{{ $product->nama_produk }}" 
                                    class="img-thumbnail"
                                    style="width: 70px; height: 70px; object-fit: cover;">
                            @else
                                <img src="{{ asset('img/no-image.png') }}" 
                                    alt="No Image" 
                                    class="img-thumbnail"
                                    style="width: 70px; height: 70px; object-fit: cover;">
                            @endif
                        </td>
                        <td class="align-middle">{{ $product->nama_produk }}</td>
                        <td class="align-middle">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                        <td class="align-middle text-lg">
                            <span class="{{ $product->stok < 10 ? 'badge text-danger' : 'badge text-primary' }}">
                                {{ $product->stok }}
                            </span>
                        </td>
                        @if(Auth::user()->role == 'admin')
                            <td class="align-middle">
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm" style="background-color: #ffc107; border: none;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-success btn-sm" style="background-color: #28a745; border: none;" data-toggle="modal" data-target="#stockModal{{ $product->id }}">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" style="background-color: #dc3545; border: none;" 
                                        onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        @endif
                    </tr>
                    @empty
                    <tr class="bg-white">
                        <td colspan="{{ Auth::user()->role == 'admin' ? '6' : '5' }}" class="text-center py-4">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <i class="fas fa-box-open fa-3x text-secondary mb-3"></i>
                                <p class="text-muted mb-3">Belum ada data produk</p>
                                @if(Auth::user()->role == 'admin')
                                    <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus mr-1"></i> Tambah Produk Pertama
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

@foreach($products as $product)
<div class="modal fade" id="stockModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="stockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="stockModalLabel">Update Stock - {{ $product->nama_produk }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('products.update-stock', $product->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="form-group">
                            <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-light" value="{{ $product->nama_produk }}" readonly>
                        </div>
                        
                        <div class="form-group mt-3">
                            <label class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stok" class="form-control" 
                                id="stockInput{{ $product->id }}" 
                                value="{{ $product->stok }}"
                                min="0"
                                required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.modal').modal({
            keyboard: true,
            backdrop: true,
            show: false
        });

        const forms = document.querySelectorAll('form[action*="update-stock"]');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const input = this.querySelector('input[name="stok"]');
                const value = parseInt(input.value) || 0;
                
                if (value <= 0) {
                    e.preventDefault();
                    alert('Stock cannot be zero or negative');
                }
            });
        });

        // Search and pagination functionality
        const searchInput = document.getElementById('search');
        const perPageSelect = document.getElementById('per-page');
        let typingTimer;

        searchInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                performSearch();
            }, 500);
        });

        perPageSelect.addEventListener('change', function() {
            window.location.href = updateQueryStringParameter(window.location.href, 'per_page', this.value);
        });

        function performSearch() {
            const searchValue = searchInput.value.trim();
            window.location.href = updateQueryStringParameter(window.location.href, 'search', searchValue);
        }

        function updateQueryStringParameter(uri, key, value) {
            const re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            const separator = uri.indexOf('?') !== -1 ? "&" : "?";
            
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + encodeURIComponent(value) + '$2');
            } else {
                return uri + separator + key + "=" + encodeURIComponent(value);
            }
        }

        // Initialize values from URL if present
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('search')) {
            searchInput.value = urlParams.get('search');
        }
        if (urlParams.has('per_page')) {
            perPageSelect.value = urlParams.get('per_page');
        }
    });
</script>
@endpush