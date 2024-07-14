<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\Absensi;
use App\Models\Kpi;
use App\Models\Payment;
use App\Models\Place;
use Illuminate\Support\Facades\Auth;

trait Absence
{
    private function saveAbsenMasuk($jadwal, $clockIn, $scheduledClockIn, $avatarName, $keterangan = '-')
    {
        $clockInTime = Carbon::parse($clockIn);
        $toleranceMinutes = 30;
        $timeDifference = $clockInTime->diffInMinutes($scheduledClockIn);

        if ($clockInTime <= $scheduledClockIn || $timeDifference <= $toleranceMinutes) {
            $late = $clockInTime > $scheduledClockIn->addMinutes($toleranceMinutes) ? 1 : 0;
            $message = $late ? 'Late Check-in' : 'Successful Check-in';

            $absensi = Absensi::create([
                'id_user' => Auth::user()->id,
                'id_place' => $jadwal->id_place,
                'clock_in' => $clockInTime,
                'tanggal' => $jadwal->tanggal,
                'terlambat' => $late,
                'keterangan' => $keterangan,
                'pict' => $avatarName,
            ]);

            return [
                'status' => true,
                'message' => $message,
                'data' => $absensi,
            ];
        }

        return [
            'status' => false,
            'message' => 'Check-in Error',
            'data' => null,
        ];
    }

    private function saveAbsenKeluar($absen, $jadwal, $clockOut, $outDate,   $scheduledClockIn, $scheduledClockOut, $keterangan = '-', $proofOfOvertime = null, $work_hour, $id_area)
    {
        // Parsing the date and time correctly
        $area = Place::where('id', $id_area)->first();
        $dateIn = Carbon::parse($absen->tanggal);
        $dateOut = Carbon::parse($outDate);
        $clockInTimeToday = Carbon::createFromFormat('Y-m-d H:i:s', $dateIn->toDateString() . " " . $absen->clock_in)->tz('Asia/Jakarta');
        $clockOutTimeToday = Carbon::createFromFormat('Y-m-d H:i:s', $dateOut->toDateString() . " " . $clockOut)->tz('Asia/Jakarta');

        // Parsing scheduled clock-in and clock-out times
        $clockInSchedule = Carbon::createFromFormat('Y-m-d H:i:s', $dateIn->toDateString() . " " . $scheduledClockIn)->tz('Asia/Jakarta');
        $clockOutSchedule = Carbon::createFromFormat('Y-m-d H:i:s', $dateIn->toDateString() . " " . $scheduledClockOut)->tz('Asia/Jakarta');

        // Adjust the clock-out schedule if it goes past midnight
        // if ($clockOutSchedule->lessThan($clockInSchedule)) {
        //     $clockOutSchedule->addDay();
        // }

        // Calculate hours worked
        $hoursWorked = ceil($clockInTimeToday->diffInHours($clockOutTimeToday));

        // Check if clock-out time is valid
        if ($clockOutTimeToday->lessThan($clockOutSchedule)) {
            return [
                'status' => false,
                'message' => 'Cannot clock out before scheduled time.',
                'data' => null,
            ];
        }

        // Check if the clock-out time is considered overtime
        $isOvertime = $clockOutTimeToday->greaterThanOrEqualTo($clockOutSchedule->copy()->addHour());

        // Calculate KPI
        $kpiClockIn = $clockInTimeToday->lessThanOrEqualTo($clockInSchedule) ? 50 : ($clockInTimeToday->equalTo($clockInSchedule) ? 25 : 25);
        $kpiClockOut = $clockOutTimeToday->greaterThanOrEqualTo($clockOutSchedule) ? ($isOvertime ? 50 : 25) : 0;
        $totalKpi = $kpiClockIn + $kpiClockOut;

        // Calculate payment based on hours worked
        $proRate = Auth::user()->salary / $area->work_hour;
        if ($hoursWorked >= $area->work_hour) {
            $cashOut = $proRate * $area->work_hour;
        } else {
            $cashOut = $proRate * $hoursWorked;
        }
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        // Return the result
        // return [
        //     'status' => true,
        //     'message' => 'Successful Check-out',
        //     'data' => [
        //         'clock_in' => $clockInTimeToday,
        //         'clock_out' => $clockOutTimeToday,
        //         'hours_worked' => $hoursWorked,
        //         'overtime' => $isOvertime,
        //         'tanggal_in' => $jadwal->tanggal,
        //         'tanggal_out' => $outDate,
        //         'cash_out' => ceil($cashOut),
        //         'total_kpi' => $totalKpi,
        //     ],
        // ];

        $updatedAbsensi = Absensi::where('id_user', Auth::user()->id)
            ->where('tanggal', $today)
            ->with('place')
            ->first();
        if ($updatedAbsensi) {
            $updateAbsensi = Absensi::where('id_user', Auth::user()->id)
                ->where('tanggal', $today)
                ->update([
                    'clock_out' => $clockOut,
                    'total_hour' => $hoursWorked,
                    'keterangan_keluar' => $keterangan,
                    'lembur' => $isOvertime,
                    'tanggal_keluar' => $dateOut,
                    'pict_keluar' => $proofOfOvertime
                ]);
        } else {
            $updateAbsensi = Absensi::where('id_user', Auth::user()->id)
                ->where('tanggal', $yesterday)
                ->update([
                    'clock_out' => $clockOut,
                    'total_hour' => $hoursWorked,
                    'keterangan_keluar' => $keterangan,
                    'lembur' => $isOvertime,
                    'tanggal_keluar' => $dateOut,
                    'pict_keluar' => $proofOfOvertime
                ]);
            $updatedAbsensi = Absensi::where('id_user', Auth::user()->id)
                ->where('tanggal', $yesterday)
                ->with('place')
                ->first();
        }



        // Create a payment record
        Payment::create([
            'id_user' => Auth::user()->id,
            'cash_out' => ceil($cashOut),
            'tanggal' => $updatedAbsensi->tanggal,
            'status' => 0,
        ]);

        // Create a KPI record
        Kpi::create([
            'user_id' => Auth::user()->id,
            'aspect_id' => 6,
            'rate' => $totalKpi,
            'period' => date('Y-m-d'),
        ]);

        return [
            'status' => true,
            'message' => 'Successful Check-out',
            'data' => $updatedAbsensi,
        ];
    }
}
