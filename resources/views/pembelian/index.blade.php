@extends('layouts.app')
@section('title', 'Pembelian')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Pembelian</h1>
        @if(Auth::user()->role === 'staff')
            <a href="{{ route('pembelian.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Penjualan
            </a>
        @endif
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('pembelian.export') }}" class="btn btn-success shadow-sm">
                    <i class="fas fa-file-excel fa-sm text-white-50 mr-1"></i> Export Excel
                </a>
                

                <div class="d-flex align-items-center">
                    <span class="mr-2">Tampilkan</span>
                    <select id="per-page" class="form-control form-control-sm" style="width: auto;">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <span class="mx-2">entri</span>

                    <span class="mr-2">Cari:</span>
                    <input type="text" id="search" class="form-control form-control-sm" style="width: 200px;" placeholder="Cari pembelian...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>Nama Pelanggan</th>
                            <th>Tanggal Penjualan</th>
                            <th>Total Harga</th>
                            <th>Dibuat Oleh</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembelians as $index => $pembelian)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $pembelian->customer_name }}</td>
                                <td class="text-center">{{ $pembelian->tanggal }}</td>
                                <td>Rp {{ number_format($pembelian->grand_total, 0, ',', '.') }}</td>
                                <td>{{ $pembelian->dibuat_oleh }}</td>
                                <td class="text-center">
                                    <a href="#" onclick="showDetail({{ $pembelian->id }})" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('pembelian.export_pdf', $pembelian->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data pembelian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $pembelians->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <!-- Modal content will be loaded here -->
        </div>
    </div>
</div>

@push('scripts')
<script>
function showDetail(id) {
    $('#detailModal').modal('show');
    fetch(`/pembelian/detail/${id}`)
        .then(response => response.text())
        .then(html => {
            $('#detailModal .modal-content').html(html);
        });
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const perPageSelect = document.getElementById('per-page');
    let typingTimer;

    searchInput.addEventListener('keyup', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            // Add your search functionality here
        }, 500);
    });

    perPageSelect.addEventListener('change', function() {
        // Add your per-page change functionality here
    });
});
</script>
@endpush
@endsection