<?php

namespace App\Repositories\MasterPlace;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Lembur;
use App\Models\Place;
use App\Models\Shift;
use App\Traits\ImageUpload;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Exception;

class MasterPlaceRepository
{
    use ImageUpload;

    public function getPlace()
    {
        try {
            $place = Place::orderByDesc('created_at')->get();
            return [
                'status' => true,
                'message' => 'Berhasil Mengambil Data Tempat Kerja',
                'data' => $place,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Data Tempat Kerja' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function makePlace(array $data)
    {
        try {
            $place = Place::create([
                'name' => $data['name'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'radius' => $data['radius'],
                'work_hour' => $data['work_hour'],
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Menambahkan Tempat Kerja',
                'data' => $place,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Menambahkan Tempat Kerja' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updatePlace(array $data)
    {
        try {
            $place = Place::where('id', $data['id'])->update([
                'name' => $data['name'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'radius' => $data['radius'],
                'work_hour' => $data['work_hour'],

            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Mengubah Detail Tempat Kerja',
                'data' => $place,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengubah Detail Tempat Kerja' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function deletePlace(array $data)
    {
        try {
            $place = Place::findOrFail($data['id']);
            $cekDataAbsen = Absensi::where('id_place', $place->id)->first();
            $cekDataShift = Shift::where('id_place', $place->id)->first();
            $cekDataJadwal = Jadwal::where('id_place', $place->id)->first();
            if ($cekDataAbsen) {
                return [
                    'status' => false,
                    'message' => 'Gagal menghapus tempat, Masih terdapat Relasi',
                    'data' => $cekDataAbsen,
                ];
            }
            if ($cekDataShift) {
                return [
                    'status' => false,
                    'message' => 'Gagal menghapus tempat, Masih terdapat Relasi',
                    'data' => $cekDataShift,
                ];
            }
            if ($cekDataJadwal) {
                return [
                    'status' => false,
                    'message' => 'Gagal menghapus tempat, Masih terdapat Relasi',
                    'data' => $cekDataJadwal,
                ];
            }
            $place->delete();

            return [
                'status' => true,
                'message' => 'Berhasil Menghapus Detail Tempat Kerja',
                'data' => $place,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Menghapus Detail Tempat Kerja - ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
