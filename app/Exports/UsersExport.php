<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class UsersExport implements FromCollection, WithHeadings, WithStyles, WithEvents, ShouldAutoSize
{
    public function collection()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            throw new \Exception('Tidak ada data pengguna untuk di-export.');
        }

        return $users->map(function ($user) {
            return [
                'Nama' => $user->name,
                'Email' => $user->email,
                'Role' => ucfirst($user->role),
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['Daftar Pengguna'], // Title row
            ['Nama', 'Email', 'Role'], // Column headers
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge title cells
        $sheet->mergeCells('A1:C1');

        // Style title
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DDEBF7']],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '4472C4'],
                ],
            ],
        ]);

        // Style headers
        $sheet->getStyle('A2:C2')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ]);

        // Style data rows
        $dataRows = 'A3:C' . $sheet->getHighestDataRow();
        $sheet->getStyle($dataRows)->applyFromArray([
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // Alternating row colors
        for ($i = 3; $i <= $sheet->getHighestDataRow(); $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle("A{$i}:C{$i}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']],
                ]);
            }
        }
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(30);
                $sheet->getColumnDimension('B')->setWidth(35);
                $sheet->getColumnDimension('C')->setWidth(15);

                // Wrap text
                $sheet->getStyle('A1:C' . $sheet->getHighestDataRow())
                      ->getAlignment()
                      ->setWrapText(true);

                // Add footer
                $footerRow = $sheet->getHighestDataRow() + 2;
                $sheet->setCellValue("A{$footerRow}", 'Diekspor pada: ' . date('d-m-Y H:i:s'));
                $sheet->mergeCells("A{$footerRow}:C{$footerRow}");
                $sheet->getStyle("A{$footerRow}")->getFont()->setItalic(true);
            },
        ];
    }
}
