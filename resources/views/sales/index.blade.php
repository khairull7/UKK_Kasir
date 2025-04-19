@extends('layouts.app')
@section('title', 'Pembelian')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Daftar Pembelian</h1>
    <div>
        @if(Auth::user()->role == 'petugas')
            <a href="{{ route('petugas.sales.create') }}" class="btn btn-primary shadow-sm mr-2">
                <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Add Penjualan
            </a>
            <a href="{{ route('petugas.sales.export.excel') }}" class="btn btn-success mr-2">
                <i class="fas fa-file-excel mr-1"></i> Unduh Excel
            </a>
        @elseif(Auth::user()->role == 'admin')
            <a href="{{ route('admin.sales.export.excel') }}" class="btn btn-success mr-2">
                <i class="fas fa-file-excel mr-1"></i> Unduh Excel
            </a>
        @endif
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<!-- Search & Entries Control -->
<form method="GET" action="{{ route(Auth::user()->role . '.sales.index') }}" class="form-inline justify-content-between mb-3">
    <div class="form-group">
        <label for="per_page" class="mr-2">Tampilkan</label>
        <select name="per_page" id="per_page" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
            @foreach([10, 25, 50, 100] as $size)
                <option value="{{ $size }}" {{ request('per_page') == $size ? 'selected' : '' }}>{{ $size }}</option>
            @endforeach
        </select>
        <span>entri</span>
    </div>

    <div class="form-group">
       <label for="search" class="mr-2">Cari:</label>
        <input type="text" name="search" id="search" class="form-control form-control-sm mr-2"
               value="{{ request('search') }}" placeholder="Nama pelanggan">
        <button type="submit" class="btn btn-sm btn-primary">Cari</button>
    </div>
</form>


<!-- Tabel Penjualan -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Nama Pelanggan</th>
                        <th>Tanggal Penjualan</th>
                        <th>Total Harga</th> 
                        <th>Dibuat Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="sales-table-body">
                    @foreach($sales as $sale)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <!-- <td>
                            @if ($sale->customer)
                                <span class="badge badge-success">{{ $sale->customer->name }}</span>
                            @else
                                <span class="badge badge-secondary">Non-Member</span>
                            @endif
                        </td> -->
                        <td>
                            <span class="badge {{ $sale->customer ? 'badge-success' : 'badge-secondary' }}">
                             {{ $sale->customer ? 'Member' : 'Non-Member' }}
                            </span>
                        </td>
                        <td class="text-center">{{ $sale->created_at->format('Y-m-d') }}</td>
                        <td>Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                        <td>{{ $sale->staff->role ?? 'Role not assigned' }}</td>
                        <td class="text-center">
                            <a href="{{ route(Auth::user()->role . '.sales.show', $sale->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Lihat
                            </a>
                            <a href="{{ route(Auth::user()->role . '.sales.pdf', $sale->id) }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-download"></i> Unduh Bukti
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $sales->links('pagination::bootstrap-4') }}
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const perPageSelect = document.getElementById('per-page');
    let typingTimer;

    searchInput.addEventListener('keyup', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            const query = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll('#sales-table-body tr');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        }, 300);
    });

    // Placeholder functionality untuk perPageSelect
    perPageSelect.addEventListener('change', function() {
        // Optional: reload page or implement pagination logic
        console.log("Show " + this.value + " entries per page.");
    });
});
</script>
@endpush

@endsection
