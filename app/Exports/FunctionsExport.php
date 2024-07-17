<?php

namespace App\Exports;

use App\Models\Functions;
use Maatwebsite\Excel\Concerns\FromCollection;

class FunctionsExport extends BaseExport
{

    public function __construct()
    {
        parent::__construct(
            new Functions(),
            ['#', 'Kode Functions', 'Nama Functions', 'Penjelasan', 'Status'],
            ['id', 'code', 'name', 'detail', 'status']
        );
    }
}
