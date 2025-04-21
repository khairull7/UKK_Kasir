<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ProductsExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting, WithEvents, ShouldAutoSize
{
    /**
     * Get the data for export
     * 
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    public function collection()
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            throw new \Exception('Tidak ada data produk untuk di-export.');
        }

        return $products->map(function ($product) {
            return [
                'nama_produk' => $product->nama_produk,
                'harga' => $product->harga, // Keep as numeric for proper formatting
                'stok' => $product->stok,
            ];
        });
    }

    /**
     * Define the headings for the export
     * 
     * @return array
     */
    public function headings(): array
    {
        return [
            ['Daftar Produk'],
            ['Nama Produk', 'Harga', 'Stok'],
        ];
    }

    /**
     * Apply styles to the sheet
     * 
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @return array
     */
    public function styles($sheet)
    {
        // Merge cells for title
        $sheet->mergeCells('A1:C1');
        
        // Title style
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '000000'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DDEBF7'],
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '4472C4'],
                ],
            ],
        ]);
        
        // Set row height for title
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Header style
        $sheet->getStyle('A2:C2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
        
        // Set row height for headers
        $sheet->getRowDimension(2)->setRowHeight(20);

        // Data rows style
        $dataRowsRange = 'A3:C' . ($sheet->getHighestDataRow());
        $sheet->getStyle($dataRowsRange)->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
        
        // Style for price column
        $sheet->getStyle('B3:B' . $sheet->getHighestDataRow())->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
        ]);
        
        // Style for stock column
        $sheet->getStyle('C3:C' . $sheet->getHighestDataRow())->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);
        
        // Add alternate row coloring for better readability
        for ($i = 3; $i <= $sheet->getHighestDataRow(); $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle('A' . $i . ':C' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F2F2F2'],
                    ],
                ]);
            }
        }
        
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * Define column formats
     * 
     * @return array
     */
    public function columnFormats(): array
    {
        // Custom Indonesian Rupiah format: "Rp" followed by thousands separator
        return [
            'B' => '"Rp"#,##0',
        ];
    }
    
    /**
     * Register events for the sheet
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $workSheet = $event->sheet->getDelegate();
                
                // Auto-fit columns for better visibility
                foreach ($workSheet->getColumnDimensions() as $column) {
                    $column->setAutoSize(true);
                }
                
                // Ensure minimum width for specific columns
                $workSheet->getColumnDimension('A')->setWidth(max($workSheet->getColumnDimension('A')->getWidth(), 30));
                $workSheet->getColumnDimension('B')->setWidth(max($workSheet->getColumnDimension('B')->getWidth(), 20));
                $workSheet->getColumnDimension('C')->setWidth(max($workSheet->getColumnDimension('C')->getWidth(), 15));
                
                // Set all cells to wrap text
                $workSheet->getStyle('A1:C' . $workSheet->getHighestDataRow())
                    ->getAlignment()
                    ->setWrapText(true);
                    
                // Add a subtle footer
                $lastRow = $workSheet->getHighestDataRow() + 2;
                $workSheet->setCellValue('A' . $lastRow, 'Diekspor pada: ' . date('d-m-Y H:i:s'));
                $workSheet->getStyle('A' . $lastRow)->getFont()->setItalic(true);
                $workSheet->mergeCells('A' . $lastRow . ':C' . $lastRow);
            },
        ];
    }
}