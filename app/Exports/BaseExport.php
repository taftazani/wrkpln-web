<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class BaseExport implements FromCollection, WithHeadings
{
    protected $model;
    protected $headings;
    protected $columns;

    public function __construct(Model $model, array $headings, array $columns)
    {
        $this->model = $model;
        $this->headings = $headings;
        $this->columns = $columns;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->model->select($this->columns)->get();
    }

    public function headings(): array
    {
        return $this->headings;
    }
}
