<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings
{
    public function collection()
{
    return Sale::with('staff')->get()->map(function ($sale) {
        return [
            'Nama Pelanggan' => $sale->customer ? 'Member' : 'Non-Member',
            // Ensure that sale_date is a Carbon instance before formatting
            'Tanggal Penjualan' => $sale->sale_date instanceof \Carbon\Carbon 
                ? $sale->sale_date->format('Y-m-d') 
                : $sale->sale_date, // in case it's a string or null, keep it as it is
            'Total Harga' => $sale->total_price,
            'Dibuat Oleh' => $sale->staff->name ?? '-',
            // 'Role' => $sale->staff->role ?? '-',
        ];
    });
}


    public function headings(): array
    {
        return [
            'Nama Pelanggan',
            'Tanggal Penjualan',
            'Total Harga',
            'Dibuat Oleh',
            // 'Role',
        ];
    }
}

