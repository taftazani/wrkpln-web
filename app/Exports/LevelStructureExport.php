<?php

namespace App\Exports;

use App\Models\LevelStructure;
use Maatwebsite\Excel\Concerns\FromCollection;

class LevelStructureExport extends BaseExport
{

    public function __construct()
    {
        parent::__construct(
            new LevelStructure(),
            ['#', 'Kode Level', 'Nama Level', 'Status'],
            ['id', 'code', 'name',  'status']
        );
    }
}
