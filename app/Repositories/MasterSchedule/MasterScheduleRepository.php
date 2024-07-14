<?php

namespace App\Repositories\MasterSchedule;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Lembur;
use App\Models\Notification;
use App\Models\Place;
use App\Models\Shift;
use App\Models\User;
use App\Traits\ImageUpload;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;

class MasterScheduleRepository
{
    use ImageUpload;
    public function getScheduleUser()
    {
        try {
            $hari_ini = date('Y-m-d');
            $userId = Auth::user()->id;

            // Get the absence record that hasn't been clocked out yet
            $absence = Absensi::where('id_user', $userId)
                ->whereNull('clock_out')
                ->whereDate('tanggal', '<=', $hari_ini)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($absence) {
                // Get the schedule corresponding to the absence date
                $scheduleDate = date('Y-m-d', strtotime($absence->tanggal));
            } else {
                // If no open absence, use today's date for the schedule
                $scheduleDate = $hari_ini;
            }

            $schedule = Jadwal::where('id_user', $userId)
                ->where('tanggal', $scheduleDate)
                ->with(['shift', 'user', 'place'])
                ->first();

            return [
                'status' => true,
                'message' => 'Berhasil Mengambil Jadwal ' . Auth::user()->name,
                'data' => [
                    'schedule' => $schedule,
                    'absence' => $absence
                ],
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Jadwal ' . Auth::user()->name . ': ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function getScheduleFilterUser($filter)
    {
        try {
            $query = Jadwal::where('id_user', Auth::user()->id)->with(['user', 'shift', 'place'])->orderByDesc('tanggal');

            switch ($filter) {
                case 'today':
                    $query->whereDate('tanggal', today());
                    break;
                case 'thisWeek':
                    $query->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'thisMonth':
                    $query->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year);
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
                'message' => 'Berhasil Mengambil Data Jadwal Staff',
                'data' => $place,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Data Jadwal Staff: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function getSchedule()
    {
        try {
            $place = Jadwal::orderByDesc('created_at')->with(['shift', 'user', 'place'])->get();
            return [
                'status' => true,
                'message' => 'Berhasil Mengambil Jadwal',
                'data' => $place,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Jadwal' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function makeSchedule(array $data)
    {
        try {
            $user = User::findOrFail($data['id_user']);
            $schedule = [];
            $startDate = Carbon::createFromFormat('Y-m-d', $data['tanggal_dari']);
            $endDate = Carbon::createFromFormat('Y-m-d', $data['tanggal_sampai']);
            $period = CarbonPeriod::create($startDate, $endDate);
            $dates = [];
            foreach ($period as $key => $date) {
                $dates[] = $date->format('Y-m-d');
            }

            foreach ($dates as $date) {
                $newSchedule = Jadwal::create([
                    'id_shift' => $data['id_shift'],
                    'id_user' => $data['id_user'],
                    'id_place' => $data['id_place'],
                    'tanggal' => $date
                ]);
                $schedule[] = $newSchedule;
            }
            $hari_ini = date('Y-m-d');
            $startDateNot = $startDate->format('Y-m-d');
            $endDateNot = $endDate->format('Y-m-d');
            $notification = Notification::create([
                'category' => 'Jadwal',
                'user_id' => $data['id_user'],
                'title' => "Pembuatan Jadwal",
                'detail' => "Halo $user->name, Jadwal Kerja Kamu Untuk tanggal $startDateNot sampai tanggal $endDateNot sudah dibuat ya. Silahkan cek halaman jadwal. Terima Kasih.",
                'tanggal' => $hari_ini
            ]);

            return [
                'status' => true,
                'message' => 'Berhasil Menambahkan Jadwal',
                'data' => $schedule,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Menambahkan Jadwal' . $e->getMessage(),
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

    public function deleteSchedule(array $data)
    {
        try {
            $schedule = Jadwal::where('id', $data['id'])->with(['place', 'shift'])->first();
            // $cekDataShift = Shift::where('id', $schedule->shift->id)->first();
            // $cekDataPlace = Place::where('id', $schedule->place->id)->first();

            // if ($cekDataShift) {
            //     return [
            //         'status' => false,
            //         'message' => 'Gagal menghapus jadwal, Masih terdapat Relasi Shift',
            //         'data' => $cekDataShift,
            //     ];
            // }
            // if ($cekDataPlace) {
            //     return [
            //         'status' => false,
            //         'message' => 'Gagal menghapus jadwal, Masih terdapat Relasi Tempat Kerja',
            //         'data' => $cekDataPlace,
            //     ];
            // }
            $schedule->delete();

            return [
                'status' => true,
                'message' => 'Berhasil Menghapus Detail Jadwal',
                'data' => $schedule,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Menghapus Detail Jadwal - ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function deleteScheduleBulk(array $data)
    {
        try {
            $schedule = [];
            foreach ($data['id'] as $id) {
                $schedules = Jadwal::where('id', $id)->with(['place', 'shift'])->first();

                $cekDataShift = Shift::where('id', $schedules->shift->id)->first();
                $cekDataPlace = Place::where('id', $schedules->place->id)->first();
                if ($cekDataShift) {
                    return [
                        'status' => false,
                        'message' => 'Gagal menghapus jadwal, Masih terdapat Relasi',
                        'data' => $cekDataShift,
                    ];
                }
                if ($cekDataPlace) {
                    return [
                        'status' => false,
                        'message' => 'Gagal menghapus jadwal, Masih terdapat Relasi',
                        'data' => $cekDataPlace,
                    ];
                }
                $schedule[] = Jadwal::where('id', $schedules->id)->delete();
            }

            return [
                'status' => true,
                'message' => 'Berhasil Menghapus Detail Jadwal',
                'data' => $schedule,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Menghapus Detail Jadwal - ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
