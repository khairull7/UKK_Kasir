@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-body">
            <h4>Selamat Datang, Administrator!</h4>

            <div class="row mt-4">
                <div class="col-md-8" style="overflow-x: auto;">
                    <canvas id="barChart" height="200"></canvas>
                </div>
                <div class="col-md-4 text-center">
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
    // Bar Chart
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($dates) !!},
            datasets: [{
                label: 'Jumlah Penjualan',
                data: {!! json_encode($sales) !!},
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

    // Pie Chart
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
