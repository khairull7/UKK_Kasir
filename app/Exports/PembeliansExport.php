<?php

namespace App\Exports;

use App\Models\Pembelians;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PembeliansExport implements FromCollection, WithHeadings, WithColumnFormatting, WithStyles, WithTitle, ShouldAutoSize, WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $hasData;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function title(): string
    {
        return 'Purchase Report';
    }

    public function collection()
    {
        $query = Pembelians::with('details.product')
            ->select('id', 'invoice_number', 'customer_name', 'grand_total', 'tanggal', 'dibuat_oleh')
            ->orderBy('tanggal', 'desc');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        }

        $pembelians = $query->get();

        $exportData = collect();

        foreach ($pembelians as $pembelian) {
            foreach ($pembelian->details as $detail) {
                $exportData->push([
                    'Invoice Number' => $pembelian->invoice_number,
                    'Customer Name' => $pembelian->customer_name,
                    'Date' => $pembelian->tanggal,
                    'Product Name' => $detail->product->nama_produk,
                    'Quantity' => $detail->quantity,
                    'Unit Price' => $detail->product->harga ?? 0, 
                    // 'Unit Price' => $detail->unit_price,
                    'Total Price' => $detail->total_price,
                    'Grand Total' => $pembelian->grand_total,
                    'Created By' => $pembelian->dibuat_oleh,
                ]);
            }
        }

        $this->hasData = $exportData->isNotEmpty();

        if (!$this->hasData) {
            $exportData->push([
                'Invoice Number' => '',
                'Customer Name' => '',
                'Date' => '',
                'Product Name' => '',
                'Quantity' => '',
                'Unit Price' => '',
                'Total Price' => '',
                'Grand Total' => '',
                'Created By' => '',
            ]);
        }

        return $exportData;
    }

    public function headings(): array
    {
        return [
            'Invoice Number',
            'Customer Name',
            'Date',
            'Product Name',
            'Quantity',
            'Unit Price',
            'Total Price',
            'Grand Total',
            'Created By'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        // Header style
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => Color::COLOR_WHITE],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => '1E90FF'], // Dodger blue
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // Body style
        $sheet->getStyle('A2:I' . $lastRow)->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_TOP,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'AAAAAA'],
                ],
            ],
        ]);

        // Alignments
        $sheet->getStyle('E2:I' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('C2:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Alternating row colors
        for ($row = 2; $row <= $lastRow; $row++) {
            $color = ($row % 2 == 0) ? 'F9F9F9' : 'FFFFFF';
            $sheet->getStyle('A' . $row . ':I' . $row)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB($color);
        }

        // Freeze pane
        $sheet->freezePane('A2');

        // Date format
        $sheet->getStyle('C2:C' . $lastRow)
            ->getNumberFormat()
            ->setFormatCode('yyyy-mm-dd');

        // If no data, merge and center message
        if (!$this->hasData) {
            $sheet->mergeCells('A2:I2');
            $sheet->setCellValue('A2', 'Tidak ada data pembelian untuk periode yang dipilih.');
            $sheet->getStyle('A2')->applyFromArray([
                'font' => [
                    'italic' => true,
                    'color' => ['argb' => 'FF0000'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);
        }
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                if ($this->hasData) {
                    $lastRow = $event->sheet->getHighestRow();

                    // Add totals
                    $event->sheet->setCellValue('G' . ($lastRow + 1), '=SUM(G2:G' . $lastRow . ')');
                    $event->sheet->setCellValue('H' . ($lastRow + 1), '=SUM(H2:H' . $lastRow . ')');

                    $event->sheet->getStyle('A' . ($lastRow + 1) . ':I' . ($lastRow + 1))->applyFromArray([
                        'font' => ['bold' => true],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['argb' => 'D9E1F2'],
                        ],
                        'borders' => [
                            'top' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                    ]);
                }

                // Auto filter
                $event->sheet->setAutoFilter('A1:I1');
            },
        ];
    }
}
