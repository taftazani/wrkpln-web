<?php

namespace App\Repositories\Report;

use App\Models\Absensi;
use App\Models\Izin;
use App\Models\Jadwal;
use App\Models\Kasbon;
use App\Models\Kpi;
use App\Models\KpiAspect;
use App\Models\Payment;
use App\Models\Shift;
use App\Traits\ImageUpload;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Exception;

class ReportRepository
{
    use ImageUpload;
    public function getReport()
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

    public function getFilteredReport($userId, $year, $month, $day, $startDate, $endDate)
    {
        try {
            $absensi_query = Absensi::where('id_user', $userId);
            $izin_query = Izin::where('id_user', $userId);
            $lembur_query = Absensi::where('id_user', $userId)->where('lembur', true);
            $tidakMasuk_query = Absensi::where('id_user', $userId)->where('terlambat', '>', 0);
            $salary_query = Payment::where('id_user', $userId);
            $advance_query = Kasbon::where('id_user', $userId);

            if ($year) {
                $absensi_query->whereYear('tanggal', $year);
                $izin_query->whereYear('tanggal_izin', $year);
                $lembur_query->whereYear('tanggal', $year);
                $tidakMasuk_query->whereYear('tanggal', $year);
                $salary_query->whereYear('tanggal', $year);
                $advance_query->whereYear('tanggal', $year);
            }

            if ($month) {
                $absensi_query->whereMonth('tanggal', $month);
                $izin_query->whereMonth('tanggal_izin', $month);
                $lembur_query->whereMonth('tanggal', $month);
                $tidakMasuk_query->whereMonth('tanggal', $month);
                $salary_query->whereMonth('tanggal', $month);
                $advance_query->whereMonth('tanggal', $month);
            }

            if ($day) {
                $absensi_query->whereDate('tanggal', $day);
                $izin_query->whereDate('tanggal_izin', $day);
                $lembur_query->whereDate('tanggal', $day);
                $tidakMasuk_query->whereDate('tanggal', $day);
                $salary_query->whereDate('tanggal', $day);
                $advance_query->whereDate('tanggal', $day);
            }

            if ($startDate && $endDate) {
                $absensi_query->whereBetween('tanggal', [$startDate, $endDate]);
                $izin_query->whereBetween('tanggal_izin', [$startDate, $endDate]);
                $lembur_query->whereBetween('tanggal', [$startDate, $endDate]);
                $tidakMasuk_query->whereBetween('tanggal', [$startDate, $endDate]);
                $salary_query->whereBetween('tanggal', [$startDate, $endDate]);
                $advance_query->whereBetween('tanggal', [$startDate, $endDate]);
            }

            $absensi = $absensi_query->get();
            $izin = $izin_query->get();
            $lembur = $lembur_query->get();
            $tidakMasuk = $tidakMasuk_query->get();
            $salary = $salary_query->get();
            $advance = $advance_query->get();

            return [
                'status' => true,
                'message' => 'Successfully fetched filtered report data',
                'data' => [
                    'absensi' => $absensi,
                    'izin' => $izin,
                    'lembur' => $lembur,
                    'tidak_masuk' => $tidakMasuk,
                    'salary' => $salary,
                    'advance' => $advance,
                ],
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
