@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @if(Auth::user()->role == 'admin')
        <div class="d-sm-flex align-items-center justify-content-between mb-3">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <h2 class="h4 mb-3 text-gray-800">
                    Selamat Datang, {{ ucfirst(Auth::user()->role) }}!
                </h2>
                
                <!-- Stats Cards Row -->
                <div class="row mb-4">
                    <!-- Total Pendapatan Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Pendapatan</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Member Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Total Member</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalMembers, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Produk Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Total Produk</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalProducts, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-box fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Pembelian Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Total Pembelian</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalPembelian, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row m-1">
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 text-white">Jumlah Pembelian</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-area">
                                <canvas id="barChart" ></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 text-white">Jumlah Produk Terjual</h6>
                        </div>

                        <div class="card-body">
                            <div class="chart-pie pt-4 pb-2 position-relative" style="height: 300px;">
                                <canvas id="pieChart"></canvas>
                            </div>
                            <div class="mt-4 text-center small">
                                @foreach($productSales as $index => $product)
                                    <span class="mr-2">
                                        <i class="fas fa-circle" style="color: {{ $colors[$index % count($colors)] }}"></i> 
                                        {{ $product->nama_produk }} ({{ $product->total_sold }})
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="container-fluid">
            <h1 class="h3 mb-3 text-primary">Dashboard</h1>
            <div class="card shadow-lg rounded">
                <div class="card-body p-4">
                    <h2 class="h4 mb-4 text-secondary">
                        Selamat Datang, {{ ucfirst(Auth::user()->role) }}!
                    </h2>

                    <div class="card bg-light border-0">
                        <div class="card-body text-center p-4">
                            <div class="text-muted mb-3">Total Penjualan Hari Ini</div>

                            <div class="h1 mb-3 font-weight-bold text-primary">{{ $todaySales }}</div>

                            <div class="text-muted mb-4">
                                Jumlah total penjualan yang terjadi hari ini.
                            </div>

                            <div class="text-muted text-sm">
                                Terakhir diperbarui: {{ now()->setTimezone('Asia/Jakarta')->format('d M Y H:i') }} WIB
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script>
        @if (Auth::user()->role === 'admin')
        const dailyLabels = @json($dailySales->pluck('date'));
        const dailyData = @json($dailySales->pluck('total'));

        const productLabels = @json($nama_produk);
        const productData = @json($actualData);
        
        new Chart(document.getElementById("barChart"), {
            type: "bar",
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: "Jumlah Penjualan",
                    backgroundColor: "#0d6efd",
                    borderColor: "#0d6efd",
                    data: dailyData
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        new Chart(document.getElementById("pieChart"), {
            type: "pie",
            data: {
                labels: productLabels,
                datasets: [{
                    data: productData,
                    backgroundColor: @json($colors)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        
        @endif
    </script>
@endpush