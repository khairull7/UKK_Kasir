<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SalesExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting
{
    public function collection()
    {
        $sales = Sale::with('staff')->get();

        if ($sales->isEmpty()) {
            throw new \Exception('Tidak ada data penjualan untuk di-export.');
        }

        // Mengembalikan data dengan mapping
        return $sales->map(function ($sale) {
            return [
                'Nama Pelanggan' => $sale->customer ? 'Member' : 'Non-Member',
                'Tanggal Penjualan' => $sale->sale_date instanceof \Carbon\Carbon 
                    ? $sale->sale_date->format('Y-m-d') 
                    : $sale->sale_date, 
                'Total Harga' => $sale->total_price,
                'Dibuat Oleh' => $sale->staff->name ?? '-',
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
        ];
    }

    public function styles($sheet)
    {
        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        $sheet->getStyle('A2:D' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        $sheet->getColumnDimension('A')->setWidth(20); 
        $sheet->getColumnDimension('B')->setWidth(20); 
        $sheet->getColumnDimension('C')->setWidth(20); 
        $sheet->getColumnDimension('D')->setWidth(20); 
    }

    public function columnFormats(): array
    {
        return [
            'B' => 'yyyy-mm-dd',  
            'C' => '#,##0',       
        ];
    }
}
