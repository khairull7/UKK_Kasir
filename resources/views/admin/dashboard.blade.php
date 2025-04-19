@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mt-4">
    <div class="card shadow mb-4">
        <div class="card-body">
            <h4>Selamat Datang, Administrator!</h4>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Total Produk Terjual</h5>
                            <p class="card-text">{{ number_format($totalProductsSold) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Total Pendapatan</h5>
                            <p class="card-text">Rp {{ number_format(array_sum($sales)) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="mb-4">Statistik Penjualan Harian</h5>
                            <div class="row">
                                <div class="col-md-6 mb-4 mb-md-0" style="overflow-x: auto;">
                                    <h6 class="text-center">Kuantitas Penjualan</h6>
                                    <canvas id="dailySalesQuantityChart" height="200"></canvas>
                                </div>
                                <div class="col-md-6" style="overflow-x: auto;">
                                    <h6 class="text-center">Revenue Harian</h6>
                                    <canvas id="dailyRevenueChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-4 offset-md-8 text-center">
                    <h6 class="mb-3">Persentase Penjualan Produk</h6>
                    <canvas id="pieChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Bar Chart (Kuantitas Penjualan Harian)
    const dailySalesQuantityChartCtx = document.getElementById('dailySalesQuantityChart').getContext('2d');
    new Chart(dailySalesQuantityChartCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($dailySalesQuantityDates) !!},
            datasets: [{
                label: 'Total Kuantitas Terjual',
                data: {!! json_encode($dailySalesQuantities) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'x',
            scales: {
                x: {
                    ticks: {
                        autoSkip: false,
                        maxRotation: 0,
                        minRotation: 0
                    }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Bar Chart (Revenue Harian)
    const dailyRevenueChartCtx = document.getElementById('dailyRevenueChart').getContext('2d');
    new Chart(dailyRevenueChartCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($dates) !!},
            datasets: [{
                label: 'Total Revenue',
                data: {!! json_encode($sales) !!},
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'x',
            scales: {
                x: {
                    ticks: {
                        autoSkip: false,
                        maxRotation: 0,
                        minRotation: 0
                    }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Pie Chart (Persentase Produk)
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($productNames) !!},
            datasets: [{
                label: 'Persentase Penjualan Produk',
                data: {!! json_encode($productPercentages) !!},
                backgroundColor: {!! json_encode($colors) !!},
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 10,
                        font: {
                            size: 10
                        }
                    }
                }
            }
        }
    });
</script>
@endpush