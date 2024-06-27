<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromArray;

class MemberExport implements FromArray
{
    protected $members;

    public function __construct(array $members)
    {
        $this->members = $members;
    }

    public function array(): array
    {
        return $this->members;
    }
}
