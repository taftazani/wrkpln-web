<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Place;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Izin;
use App\Models\Kasbon;
use App\Models\Kpi;

class DashboardController extends Controller
{
    public function getDashboardUserData()
    {
        $userId = auth()->id();

        $performance = Kpi::where('user_id', $userId)->average('rate');
        $salary = Payment::where('id_user', $userId)->where('status', 0)->whereMonth('tanggal', now()->month)->sum('cash_out');
        $advance = Kasbon::where('id_user', $userId)->where('status', 1)->whereMonth('tanggal', now()->month)->sum('cash_out');
        $absencesMonth = Absensi::where('id_user', $userId)->whereMonth('tanggal', now()->month)->count();
        $absencesWeek = Absensi::where('id_user', $userId)->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $permissionsWeek = Izin::where('id_user', $userId)->whereBetween('tanggal_izin', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $overtimeWeek = Absensi::where('id_user', $userId)->where('lembur', 1)->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])->count();

        return response()->json([
            'performance' => $performance,
            'salary' => $salary,
            'advance' => $advance,
            'absencesMonth' => $absencesMonth,
            'absencesWeek' => $absencesWeek,
            'permissionsWeek' => $permissionsWeek,
            'overtimeWeek' => $overtimeWeek
        ]);
    }
    public function getTotalStaff()
    {
        $totalStaff = User::count();
        return response()->json(['totalStaff' => $totalStaff]);
    }

    public function getTotalPlaces()
    {
        $totalPlaces = Place::count();
        return response()->json(['totalPlaces' => $totalPlaces]);
    }

    public function getTotalPayouts()
    {
        $totalPayouts = Payment::where('status', 1)->sum('cash_out');
        return response()->json(['totalPayouts' => $totalPayouts]);
    }

    public function getTotalAdvances()
    {
        $totalAdvances = Kasbon::where('status', 1)->sum('cash_out');
        return response()->json(['totalAdvances' => $totalAdvances]);
    }

    public function getChartData(Request $request)
    {
        $userId = $request->user()->id;

        // Fetch performance data
        $performances = Kpi::selectRaw('MONTH(period) as month, AVG(rate) as avg_rate')
            ->groupByRaw('MONTH(period)')
            ->pluck('avg_rate', 'month');

        // Fetch advance data
        $advances = Kasbon::selectRaw('MONTH(tanggal) as month, SUM(cash_out) as total_cash_out')
            ->where('status', 1)
            ->groupByRaw('MONTH(tanggal)')
            ->pluck('total_cash_out', 'month');

        // Create the months array
        $months = [];
        $advancesData = [];
        $performancesData = [];

        foreach (range(1, 12) as $month) {
            $months[] = date('F', mktime(0, 0, 0, $month, 10)); // Get the month name
            $advancesData[] = $advances->get($month, 0); // Default to 0 if no data
            $performancesData[] = $performances->get($month, 0); // Default to 0 if no data
        }

        return response()->json([
            'months' => $months,
            'advances' => $advancesData,
            'performances' => $performancesData
        ]);
    }


    public function getUpcomingBirthdays()
    {
        $birthdays = User::get(['name', 'tgl_lahir', 'profile_image']);
        return response()->json($birthdays);
    }
}
