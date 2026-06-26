<?php

namespace App\Exports;

use App\Models\Good;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class TransactionExport implements FromArray, WithColumnFormatting
{
    protected $goods;

    public function __construct(array $goods)
    {
        $this->goods = $goods;
    }

    public function array(): array
    {
        return $this->goods;
    }

    public function columnFormats(): array
    {
        return [
            'F' => '#,##', 
            'G' => '#,##',     
        ];
    }
}
