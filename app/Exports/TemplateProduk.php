<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TemplateProduk implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    public function title(): string
    {
        return 'Produk';
    }

    public function headings(): array
    {
        return [
            'nama_produk',
            'kategori',
            'supplier',
            'satuan',
            'harga_jual',
            'stok_minimum',
            'stok_awal',
            'harga_modal',
            'catatan',
        ];
    }

    public function array(): array
    {
        return [
            ['Pupuk Urea 50kg',    'Pupuk',     'PT Supplier A',   'karung', 150000, 5,  10, 120000, 'Contoh stok awal'],
            ['Pestisida Cair 1L',  'Pestisida', '',                'botol',   75000, 3,   5,  60000, ''],
            ['Benih Padi Premium', 'Benih',     'CV Benih Unggul', 'kg',      25000, 10,  0,      0, ''],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Style baris header
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size'  => 11,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '059669'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'D1FAE5'],
                ],
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(24);

        // Zebra stripe data
        foreach ([2, 4] as $row) {
            $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F0FDF4'],
                ],
            ]);
        }

        // Border semua data
        $sheet->getStyle('A1:I4')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'E2E8F0'],
                ],
            ],
        ]);

        return [];
    }
}
