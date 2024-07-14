<?php

namespace App\Repositories\Kpi;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Kpi;
use App\Models\KpiAspect;
use App\Models\Shift;
use App\Traits\ImageUpload;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Exception;

class KpiRepository
{
    use ImageUpload;
    public function getKpiAspects()
    {
        try {
            $kpiAspects = KpiAspect::all();
            return [
                'status' => true,
                'message' => 'Successfully fetched KPI Aspects Data',
                'data' => $kpiAspects,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function getKpiData($userId, $dateRange)
    {
        try {
            $userId = intval($userId);
            $query = Kpi::where('user_id', $userId)->with('aspect');
            // $dates = explode(',', $dateRange);

            if (!empty($dateRange)) {
                $dates = explode(',', $dateRange);
                $startDate = Carbon::parse($dates[0]);
                $endDate = Carbon::parse($dates[1]);
                $query->whereBetween('period', [$startDate, $endDate]);
            }

            $kpiData = $query->get();

            return [
                'status' => true,
                'message' => "Successfully fetched KPI data",
                'data' => $kpiData,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Failed to fetch KPI data: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function getKpi()
    {
        try {
            $place = Kpi::orderByDesc('created_at')->with('user')->get();
            return [
                'status' => true,
                'message' => 'Berhasil Mengambil Data Kpi',
                'data' => $place,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Data Kpi' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function getKpiUser($filter)
    {
        try {
            $query = Kpi::where('id_user', Auth::user()->id)->with('user')->orderByDesc('created_at');

            switch ($filter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'thisWeek':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'thisMonth':
                    $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    break;
                case 'all':
                    // No additional filter needed
                    break;
                default:
                    return [
                        'status' => false,
                        'message' => 'Invalid filter option',
                        'data' => null,
                    ];
            }

            $place = $query->get();

            return [
                'status' => true,
                'message' => 'Berhasil Mengambil Data Kpi Staff',
                'data' => $place,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Data Kpi Staff: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function makeKpi(array $data)
    {
        try {
            $period = Carbon::parse($data['period']);
            $place = Kpi::create([
                'user_id' => $data['user_id'],
                'aspect_id' => $data['aspect_id'],
                'rate' => $data['rate'],
                'period' => $period,
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Menambahkan Kpi',
                'data' => $place,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Menambahkan Kpi' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateKpi(array $data)
    {
        try {
            $kelengkapan = [];
            if ($data['pict']) {
                $kelengkapan = $this->uploadImage($data['pict'], 'izin');
            }
            $place = Kpi::where('id', $data['id'])->update([
                'id_user' => $data['id_user'],
                'tanggal_izin' => Carbon::parse($data['tanggal_izin'])->format('Y-m-d'),
                'tanggal_izin_selesai' => Carbon::parse($data['tanggal_izin_selesai'])->format('Y-m-d'),
                'kelengkapan' => $kelengkapan,
                'keperluan' => $data['keperluan'],
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Mengubah Detail Kpi',
                'data' => $place,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengubah Detail Kpi' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateKpiApprove(array $data)
    {
        try {
            $item = Kpi::findOrFail($data['id']);
            $tanggal_izin = Carbon::parse($item->tanggal_izin);
            $tanggal_izin_selesai = Carbon::parse($item->tanggal_izin_selesai);

            $izin = Kpi::where('id', $data['id'])->update([
                'status' => 1,
            ]);

            if ($izin) {
                Jadwal::where('id_user', $item->id_user)
                    ->whereBetween('tanggal', [$tanggal_izin, $tanggal_izin_selesai])
                    ->delete();
            }

            return [
                'status' => true,
                'message' => 'Berhasil Approve Kpi',
                'data' => Kpi::find($izin),
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Approve Kpi' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateKpiReject(array $data)
    {
        try {
            $izin = Kpi::where('id', $data['id'])->update([
                'status' => 2,
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Reject Kpi',
                'data' => Kpi::find($izin),
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Reject Kpi' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function deleteKpi(array $data)
    {
        try {
            $place = Kpi::findOrFail($data['id']);
            $cekDataAbsen = Absensi::where('id_place', $place->id)->first();
            $cekDataShift = Shift::where('id_place', $place->id)->first();
            $cekDataJadwal = Jadwal::where('id_place', $place->id)->first();
            if ($cekDataAbsen) {
                return [
                    'status' => false,
                    'message' => 'Gagal menghapus izin, Masih terdapat Relasi',
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
                'message' => 'Berhasil Menghapus Detail Kpi',
                'data' => $place,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Menghapus Detail Kpi - ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
