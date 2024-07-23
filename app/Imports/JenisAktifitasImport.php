<?php

namespace App\Imports;

use App\Models\JenisAktifitas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JenisAktifitasImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new JenisAktifitas([
            'kode_jenis_aktifitas' => $row['kode_jenis_aktifitas'],
            'nama_jenis_aktifitas' => $row['nama_jenis_aktifitas'],
            'flag_tab_menu_personal' => $row['flag_tab_menu_personal'],
            'batas_waktu_kunjungan' => $row['batas_waktu_kunjungan'],
            'batas_maksimal_umur_progress' => $row['batas_maksimal_umur_progress'],
            'status' => $row['status'],
        ]);
    }
}
