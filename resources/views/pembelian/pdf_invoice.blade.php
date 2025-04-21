<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $pembelian->invoice_number }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .header h1 {
            font-size: 28px;
            margin: 0 0 5px 0;
            color: #2c3e50;
        }
        .header p {
            font-size: 14px;
            color: #7f8c8d;
            margin: 0;
        }
        .info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .customer-info {
            width: 48%;
            margin-bottom: 15px;
            float: left;
        }
        .transaction-info {
            width: 48%;
            margin-bottom: 15px;
            float: right;
        }
        .info-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #eee;
            color: #2c3e50;
        }
        .info-content {
            font-size: 14px;
        }
        .info-content strong {
            display: inline-block;
            width: 120px;
            color: #7f8c8d;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 14px;
            clear: both;
        }
        .table th {
            background-color: blue;
            color: white;
            text-align: left;
            padding: 12px 15px;
        }
        .table td {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
        }
        .table tbody tr:hover {
            background-color: #f5f5f5;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .table tfoot td {
            font-weight: bold;
            background-color: #f8f9fa;
            border-top: 2px solid #eee;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 14px;
            color: #7f8c8d;
        }
        .thank-you {
            font-style: italic;
            color: blue;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <h1>Invoice #{{ $pembelian->invoice_number }}</h1>
            <p>{{ \Carbon\Carbon::parse($pembelian->created_at)->translatedFormat('d F Y') }} | {{ \Carbon\Carbon::parse($pembelian->created_at)->format('H:i') }} WIB</p>
        </div>

        <!-- Customer and Transaction Info Section -->
        <div class="info">
            <div class="customer-info">
                <div class="info-title">Informasi Pelanggan</div>
                @if($member)
                <div class="info-content">
                    <strong>Status:</strong> Member<br>
                    <strong>Nama:</strong> {{ $pembelian->customer_name }}<br>
                    <strong>No. HP:</strong> {{ $member->phone_number }}<br>
                    <strong>Poin:</strong> {{ $member->points }} Poin<br>
                    <strong>Member Sejak:</strong> {{ \Carbon\Carbon::parse($member->member_since)->translatedFormat('d F Y') }}
                </div>
                @else
                <div class="info-content">
                    <strong>Status:</strong> Bukan Member<br>
                    <strong>Nama:</strong> {{ $pembelian->customer_name ?? '-' }}<br>
                    <strong>Member Sejak:</strong> Tidak terdaftar
                </div>
                @endif
            </div>
            
            <div class="transaction-info">
                <div class="info-title">Informasi Transaksi</div>
                <div class="info-content">
                    <strong>Kasir:</strong> {{ $pembelian->dibuat_oleh }}<br>
                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($pembelian->tanggal)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') }}<br>
                    <strong>Waktu:</strong> {{ \Carbon\Carbon::parse($pembelian->created_at)->setTimezone('Asia/Jakarta')->format('H:i') }} WIB<br>
                    <strong>Invoice:</strong> #{{ $pembelian->invoice_number }}
                </div>
            </div>
        </div>

        <!-- Order Items Section -->
        <table class="table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pembelian->details as $detail)
                <tr>
                    <td>{{ $detail->product->nama_produk }}</td>
                    <td class="text-center">{{ $detail->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($detail->product->harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($detail->total_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right">Total Pembelian</td>
                    <td class="text-right">Rp {{ number_format($pembelian->grand_total, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
            @if($pembelian->pembayaran)
            <tr>
                <td colspan="3" class="text-right">Total Bayar</td>
                <td class="text-right">Rp {{ number_format($pembelian->pembayaran->jumlah_bayar, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="text-right">Kembalian</td>
                <td class="text-right">Rp {{ number_format($pembelian->pembayaran->kembalian, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="text-right">Metode Pembayaran</td>
                <td class="text-right">{{ ucfirst($pembelian->pembayaran->metode_pembayaran) }}</td>
            </tr>
            @endif

        </table>

        <!-- Footer Section -->
        <div class="footer">
            <p class="thank-you">Terima kasih atas pembelian Anda!</p>
            <p>Silahkan berkunjung kembali</p>
        </div>
    </div>
</body>
</html>
