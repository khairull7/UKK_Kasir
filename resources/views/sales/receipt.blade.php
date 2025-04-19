<!DOCTYPE html>
<html>
<head>
    <title>Invoice - Bukti Pembelian</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 20px; }
        h2 { text-align: center; margin-bottom: 20px; }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .invoice-header div {
            text-align: left;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        td, th { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f8f9fa; }
        .total-row th {
            text-align: right;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

    <!-- Header Invoice -->
    <div class="invoice-header">
        <div>
            <h2>Bukti Pembelian</h2>
        </div>
        <div style="text-align: right;">
            <p><strong>Invoice:</strong> #{{ $sale->id }}</p>
            <p><strong>Tanggal:</strong> {{ $sale->created_at->format('d F Y H:i') }}</p>
        </div>
    </div>

    <p><strong>Nama Pelanggan:</strong> {{ $sale->customer->name ?? 'Non-Member' }}</p>

    <!-- Product Table -->
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->details as $detail)
                <tr>
                    <td>{{ $detail->product->name }}</td>
                    <td>Rp {{ number_format($detail->product->price, 0, ',', '.') }}</td>
                    <td>{{ $detail->amount }}</td>
                    <td>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <th colspan="3">Total</th>
                <th>Rp {{ number_format($sale->total_price, 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>

</body>
</html>
