<?php

namespace App\Repositories\MasterShift;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Lembur;
use App\Models\Place;
use App\Models\Shift;
use App\Traits\ImageUpload;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;

class MasterShiftRepository
{
    use ImageUpload;

    public function getShift()
    {
        try {
            $place = Shift::orderByDesc('created_at')->with('place')->get();
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
    public function makeShift(array $data)
    {
        try {
            $jam_masuk = Carbon::parse($data['clock_in']);
            $jam_keluar = Carbon::parse($data['clock_out']);
            $schedule = Shift::create([
                'id_place' => $data['id_place'],
                'clock_in' => $jam_masuk,
                'clock_out' => $jam_keluar,
                'name' => $data['name']
            ]);

            return [
                'status' => true,
                'message' => 'Berhasil Menambahkan Shift',
                'data' => $schedule,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Menambahkan Shift' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateShift(array $data)
    {
        try {
            $jam_masuk = Carbon::parse($data['clock_in']);
            $jam_keluar = Carbon::parse($data['clock_out']);
            $schedule = Shift::where('id', $data['id'])->update([
                'id_place' => $data['id_place'],
                'clock_in' => $jam_masuk,
                'clock_out' => $jam_keluar,
                'name' => $data['name']
            ]);

            return [
                'status' => true,
                'message' => 'Berhasil Mengubah Detail Shift',
                'data' => $schedule,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengubah Detail Shift' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function deleteShift(array $data)
    {
        try {
            $shift = Shift::where('id', $data['id'])->with(['place'])->first();
            $cekDataJadwal = Jadwal::where('id_shift', $shift->id)->first();

            if ($cekDataJadwal) {
                return [
                    'status' => false,
                    'message' => 'Gagal menghapus Shift, Masih terdapat Relasi',
                    'data' => $cekDataJadwal,
                ];
            }

            $shift->delete();

            return [
                'status' => true,
                'message' => 'Berhasil Menghapus Detail Shift',
                'data' => $shift,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Menghapus Detail Shift - ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
