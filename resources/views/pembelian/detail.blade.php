<div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5)">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-receipt mr-2"></i>Detail Penjualan
                </h5>
                <a href="{{ route('pembelian.index') }}" class="close text-white">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>

            <div class="modal-body">
                <!-- Dual Column Layout -->
                <div class="row mb-4">
                    <!-- Informasi Pelanggan (Left) -->
                    <div class="col-md-6 pr-md-2">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title text-primary mb-3">
                                    <i class="fas fa-user-circle mr-2"></i>Informasi Pelanggan
                                </h6>
                                
                                @php
                                    $member = \App\Models\Member::where('name', $pembelian->customer_name)->first();
                                @endphp
                                
                                @if($member)
                                    <p class="mb-2">
                                        <span class="font-weight-bold">Status:</span> 
                                        <span class="badge badge-success">Member</span>
                                    </p>
                                    <p class="mb-2">
                                        <span class="font-weight-bold">Nama:</span> 
                                        {{ $pembelian->customer_name }}
                                    </p>
                                    <p class="mb-2">
                                        <span class="font-weight-bold">No. HP:</span> 
                                        {{ $member->phone_number }}
                                    </p>
                                    <p class="mb-2">
                                        <span class="font-weight-bold">Poin:</span> 
                                        <span class="badge badge-info">{{ $member->points }} Poin</span>
                                    </p>
                                    <p class="mb-0">
                                        <span class="font-weight-bold">Member Sejak:</span> 
                                        {{ \Carbon\Carbon::parse($member->member_since)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') }}
                                    </p>
                                @else
                                    <p class="mb-2">
                                        <span class="font-weight-bold">Status:</span> 
                                        <span class="badge badge-secondary">Bukan Member</span>
                                    </p>
                                    <p class="mb-2">
                                        <span class="font-weight-bold">Nama:</span> 
                                        {{ $pembelian->customer_name ?? '-' }}
                                    </p>
                                    <p class="mb-0 text-muted">Tidak terdaftar sebagai member</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Transaksi (Right) -->
                    <div class="col-md-6 pl-md-2">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title text-primary mb-3">
                                    <i class="fas fa-info-circle mr-2"></i>Informasi Transaksi
                                </h6>
                                <p class="mb-2">
                                    <span class="font-weight-bold">Tanggal:</span> 
                                    {{ $pembelian->created_at->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') }}
                                </p>
                                <p class="mb-2">
                                    <span class="font-weight-bold">Waktu:</span> 
                                    {{ $pembelian->created_at->format('H:i') }} WIB
                                </p>
                                <p class="mb-0">
                                    <span class="font-weight-bold">Kasir:</span> 
                                    {{ $pembelian->dibuat_oleh }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items Section (Full Width Below) -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-0">
                        <h6 class="card-title bg-light p-3 mb-0 text-primary">
                            <i class="fas fa-shopping-basket mr-2"></i>Detail Pembelian
                        </h6>
                        
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-left">Produk</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-right">Harga Satuan</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pembelian->details as $detail)
                                    <tr>
                                        <td class="align-middle">{{ $detail->product->nama_produk }}</td>
                                        <td class="text-center align-middle">{{ $detail->quantity }}</td>
                                        <td class="text-right align-middle">Rp {{ number_format($detail->product->harga, 0, ',', '.') }}</td>
                                        <td class="text-right align-middle font-weight-bold">Rp {{ number_format($detail->total_price, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="3" class="text-right font-weight-bold py-3">Total Pembelian</td>
                                        <td class="text-right font-weight-bold py-3 text-primary">
                                            Rp {{ number_format($pembelian->grand_total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light">
                <a href="{{ route('pembelian.index') }}" class="btn btn-primary px-4">
                    <i class="fas fa-times mr-2"></i>Tutup
                </a>
            </div>
        </div>
    </div>
</div>