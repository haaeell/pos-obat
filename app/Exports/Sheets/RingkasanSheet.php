<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Models\PengaturanToko;

class RingkasanSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(
        protected array $ringkasan,
        protected array $saldo,
        protected string $dari,
        protected string $sampai
    ) {}

    public function title(): string
    {
        return 'Laporan Keuangan';
    }

    public function columnWidths(): array
    {
        return ['A' => 35, 'B' => 25];
    }

    public function array(): array
    {
        $toko = PengaturanToko::instance();
        $rp   = fn($v) => 'Rp ' . number_format($v, 0, ',', '.');

        return [
            [$toko->nama_toko ?? 'Toko'],
            ['Laporan Keuangan'],
            ['Periode: ' . \Carbon\Carbon::parse($this->dari)->format('d/m/Y') . ' s/d ' . \Carbon\Carbon::parse($this->sampai)->format('d/m/Y')],
            ['Dicetak: ' . now()->format('d/m/Y H:i')],
            [''],
            ['RINGKASAN PENJUALAN', ''],
            ['Total Penjualan', $rp($this->ringkasan['total_penjualan'])],
            ['Total HPP (FIFO)', $rp($this->ringkasan['total_hpp'])],
            ['Laba Kotor', $rp($this->ringkasan['laba_kotor'])],
            ['Margin Laba', number_format($this->ringkasan['margin'], 1) . '%'],
            ['Jumlah Transaksi', $this->ringkasan['jumlah_transaksi'] . ' transaksi'],
            [''],
            ['POSISI ASET', ''],
            ['Kas', $rp($this->saldo['kas'])],
            ['Piutang Pelanggan', $rp($this->saldo['piutang'])],
            ['Nilai Stok Barang', $rp($this->saldo['nilai_stok'])],
            ['Total Aset', $rp($this->saldo['kas'] + $this->saldo['piutang'] + $this->saldo['nilai_stok'])],
            [''],
            ['KEWAJIBAN & EKUITAS', ''],
            ['Sisa Hutang Modal', $rp($this->saldo['hutang_modal'])],
            ['Total Pencairan Modal', $rp($this->saldo['total_pencairan_modal'])],
            ['Total Cicilan Terbayar', $rp($this->saldo['total_cicilan_terbayar'])],
            ['Ekuitas Bersih', $rp($this->saldo['ekuitas'])],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $green  = '059669';
        $white  = 'FFFFFF';
        $light  = 'ECFDF5';
        $border = ['borders' => ['outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1FAE5']]]];

        // Nama toko
        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('A2:B2');
        $sheet->mergeCells('A3:B3');
        $sheet->mergeCells('A4:B4');

        return [
            1  => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => $green]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            2  => [
                'font' => ['bold' => true, 'size' => 11],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            3  => [
                'font' => ['size' => 10, 'color' => ['rgb' => '6B7280']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            4  => [
                'font' => ['size' => 10, 'color' => ['rgb' => '6B7280']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            6  => [
                'font' => ['bold' => true, 'color' => ['rgb' => $white]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $green]]
            ],
            13 => [
                'font' => ['bold' => true, 'color' => ['rgb' => $white]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $green]]
            ],
            19 => [
                'font' => ['bold' => true, 'color' => ['rgb' => $white]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $green]]
            ],
            // Baris total
            17 => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $light]]],
            23 => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $light]]],
            'A' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]],
            'B' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]],
        ];
    }
}
