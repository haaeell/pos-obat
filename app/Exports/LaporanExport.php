<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanExport implements WithMultipleSheets
{
    public function __construct(
        protected array $ringkasan,
        protected array $saldo,
        protected string $dari,
        protected string $sampai
    ) {}

    public function sheets(): array
    {
        return [
            new Sheets\RingkasanSheet($this->ringkasan, $this->saldo, $this->dari, $this->sampai),
        ];
    }
}
