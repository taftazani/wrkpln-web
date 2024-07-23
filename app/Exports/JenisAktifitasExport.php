<?php

namespace App\Exports;

use App\Models\JenisAktifitas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JenisAktifitasExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return JenisAktifitas::all();
    }

    public function headings(): array
    {
        return [
            'kode_jenis_aktifitas',
            'nama_jenis_aktifitas',
            'flag_tab_menu_personal',
            'batas_waktu_kunjungan',
            'batas_maksimal_umur_progress',
            'status',
        ];
    }
}
