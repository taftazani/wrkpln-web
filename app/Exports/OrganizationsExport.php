<?php

namespace App\Exports;

use App\Models\Organization;

class OrganizationsExport extends BaseExport
{
    public function __construct()
    {
        parent::__construct(
            new Organization,
            ['#', 'Kode Organization', 'Nama Organization', 'Penjelasan', 'Status'],
            ['id', 'code', 'name', 'detail', 'status']
        );
    }
}
