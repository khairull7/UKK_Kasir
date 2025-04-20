@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @if(Auth::user()->role == 'admin')
        <div class="d-sm-flex align-items-center justify-content-between mb-3">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
            <h2 class="h4 mb-1 text-gray-800">
                Selamat Datang, {{ ucfirst(Auth::user()->role) }}!
            </h2>
            </div>

            <div class="row m-1">
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Jumlah Pembelian</h6>
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
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Distribusi Produk Terjual</h6>
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
        {{-- Staff content remains the same --}}
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
    <!-- Include Chart.js and Moment.js Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script>
        @if (Auth::user()->role === 'admin')
        // Prepare data for bar chart
        const dailyLabels = @json($dailySales->pluck('date'));
        const dailyData = @json($dailySales->pluck('total'));

        // Prepare data for pie chart
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

        // Pie chart
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