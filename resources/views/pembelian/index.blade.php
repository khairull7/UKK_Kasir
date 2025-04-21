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
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="75" {{ request('per_page') == 75 ? 'selected' : '' }}>75</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="mx-2">entri</span>

                    <span class="mr-2">Cari:</span>
                    <input type="text" id="search" class="form-control form-control-sm" style="width: 200px;" placeholder="Cari pembelian..." value="{{ request('search') }}">
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
                                <td class="text-center">{{ ($pembelians->currentPage() - 1) * $pembelians->perPage() + $index + 1 }}</td>
                                <td>{{ $pembelian->customer_name }}</td>
                                <td class="text-center">{{ $pembelian->tanggal }}</td>
                                <td>Rp {{ number_format($pembelian->grand_total, 0, ',', '.') }}</td>
                                <td>{{ $pembelian->dibuat_oleh }}</td>
                                <td class="text-center">
                                    <a href="#" onclick="showDetail({{ $pembelian->id }})" class="btn btn-warning btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('pembelian.export_pdf', $pembelian->id) }}" class="btn btn-danger btn-sm">
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
                {{ $pembelians->appends(request()->query())->links('pagination::bootstrap-4') }}
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

    function updatePage() {
        const search = searchInput.value;
        const perPage = perPageSelect.value;

        const url = new URL(window.location.href);
        url.searchParams.set('search', search);
        url.searchParams.set('per_page', perPage);

        window.location.href = url.toString();
    }

    searchInput.addEventListener('keyup', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(updatePage, 500);
    });

    perPageSelect.addEventListener('change', updatePage);
});
</script>
@endpush
@endsection
