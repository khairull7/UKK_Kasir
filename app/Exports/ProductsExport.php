<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ProductsExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting
{
    /**
     * Ambil data yang akan di-export ke Excel
     */
    public function collection()
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            throw new \Exception('Tidak ada data produk untuk di-export.');
        }

        return $products->map(function ($product) {
            return [
                'Nama' => $product->name,
                'Harga' => 'Rp' . number_format($product->price, 0, ',', '.'),
                'Stok' => $product->stock,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Harga',
            'Stok',
        ];
    }

    public function styles($sheet)
    {
        $sheet->getStyle('A1:C1')->applyFromArray([
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

        $sheet->getStyle('A2:C' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'B' => '#,##0', 
        ];
    }
}
