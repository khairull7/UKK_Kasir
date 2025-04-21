<div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(30, 30, 30, 0.7); font-family: 'Open Sans', sans-serif;">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header bg-white border-bottom-0 py-4 px-5">
                <h5 class="modal-title text-dark fw-bold">
                    <i class="fas fa-receipt me-2 text-primary"></i> Rincian Transaksi
                </h5>
                <a href="{{ route('pembelian.index') }}" class="btn btn-sm btn-outline-secondary rounded-circle">
                    <i class="fas fa-times"></i>
                </a>
            </div>

            <div class="modal-body bg-light px-5 pt-4 pb-0">
                <div class="row g-4">
                    <!-- Informasi Pelanggan -->
                    <div class="col-md-6">
                        <div class="bg-white rounded-3 shadow-sm p-4 h-100">
                            <h6 class="mb-3 text-primary"><i class="fas fa-user me-2"></i> Data Pelanggan</h6>
                            @php
                                $member = \App\Models\Member::where('name', $pembelian->customer_name)->first();
                            @endphp
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-2"><strong>Status:</strong>
                                    @if($member)
                                        <span class="badge bg-success">Member</span>
                                    @else
                                        <span class="badge bg-secondary">Bukan Member</span>
                                    @endif
                                </li>
                                <li class="mb-2"><strong>Nama:</strong> {{ $pembelian->customer_name ?? '-' }}</li>
                                @if($member)
                                    <li class="mb-2"><strong>No. HP:</strong> {{ $member->phone_number }}</li>
                                    <li class="mb-2"><strong>Poin:</strong> <span class="badge bg-info text-white">{{ $member->points }} Poin</span></li>
                                    <li><strong>Member Sejak:</strong> {{ \Carbon\Carbon::parse($member->member_since)->translatedFormat('d F Y') }}</li>
                                @else
                                    <li class="text-muted">Tidak terdaftar sebagai member.</li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <!-- Informasi Transaksi -->
                    <div class="col-md-6">
                        <div class="bg-white rounded-3 shadow-sm p-4 h-100">
                            <h6 class="mb-3 text-primary"><i class="fas fa-info-circle me-2"></i> Informasi Transaksi</h6>
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-2"><strong>Tanggal:</strong> {{ $pembelian->created_at->translatedFormat('d F Y') }}</li>
                                <li class="mb-2"><strong>Waktu:</strong> {{ $pembelian->created_at->format('H:i') }} WIB</li>
                                <li><strong>Kasir:</strong> {{ $pembelian->dibuat_oleh }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Detail Pembelian -->
                <div class="mt-5">
                    <div class="bg-white rounded-3 shadow-sm p-0">
                        <div class="px-4 py-3 border-bottom bg-primary text-white rounded-top">
                            <i class="fas fa-shopping-basket me-2"></i> Detail Pembelian
                        </div>
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pembelian->details as $detail)
                                        <tr>
                                            <td>{{ $detail->product->nama_produk }}</td>
                                            <td class="text-center">{{ $detail->quantity }}</td>
                                            <td class="text-end">Rp {{ number_format($detail->product->harga, 0, ',', '.') }}</td>
                                            <td class="text-end fw-bold">Rp {{ number_format($detail->total_price, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Total Pembelian</td>
                                        <td class="text-end fw-bold text-primary">Rp {{ number_format($pembelian->grand_total, 0, ',', '.') }}</td>
                                    </tr>
                                    @if($pembelian->pembayaran)
                                        <tr>
                                            <td colspan="3" class="text-end fw-bold">Total Bayar</td>
                                            <td class="text-end text-success fw-bold">Rp {{ number_format($pembelian->pembayaran->jumlah_bayar, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end fw-bold">Kembalian</td>
                                            <td class="text-end text-danger fw-bold">Rp {{ number_format($pembelian->pembayaran->kembalian, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end fw-bold">Metode Pembayaran</td>
                                            <td class="text-end">{{ ucfirst($pembelian->pembayaran->metode_pembayaran) }}</td>
                                        </tr>
                                    @endif
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-white px-5 py-3">
                <a href="{{ route('pembelian.index') }}" class="btn btn-outline-primary px-4">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
