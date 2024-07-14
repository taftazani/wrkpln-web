<?php

namespace App\Repositories\Izin;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Lembur;
use App\Models\Izin;
use App\Models\Notification;
use App\Models\Shift;
use App\Models\User;
use App\Traits\ImageUpload;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Exception;

class IzinRepository
{
    use ImageUpload;

    public function getIzin()
    {
        try {
            $place = Izin::orderByDesc('created_at')->with('user')->get();
            return [
                'status' => true,
                'message' => 'Berhasil Mengambil Data Izin',
                'data' => $place,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Data Izin' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function getIzinUser($filter)
    {
        try {
            $query = Izin::where('id_user', Auth::user()->id)->with('user')->orderByDesc('created_at');

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
                'message' => 'Berhasil Mengambil Data Izin Staff',
                'data' => $place,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Data Izin Staff: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function makeIzin(array $data)
    {
        try {
            $tanggal_izin = Carbon::parse($data['tanggal_izin']);
            $tanggal_izin_selesai = Carbon::parse($data['tanggal_izin_selesai']);
            $kelengkapan = $this->uploadImage($data['pict'], 'izin');
            $place = Izin::create([
                'id_user' => Auth::user()->id,
                'tanggal_izin' => $tanggal_izin,
                'tanggal_izin_selesai' => $tanggal_izin_selesai,
                'kelengkapan' => $kelengkapan,
                'keperluan' => $data['keperluan'],
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Mengajukan Izin',
                'data' => $place,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengajukan Izin' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateIzin(array $data)
    {
        try {
            $kelengkapan = [];
            if ($data['pict']) {
                $kelengkapan = $this->uploadImage($data['pict'], 'izin');
            }
            $place = Izin::where('id', $data['id'])->update([
                'id_user' => $data['id_user'],
                'tanggal_izin' => Carbon::parse($data['tanggal_izin'])->format('Y-m-d'),
                'tanggal_izin_selesai' => Carbon::parse($data['tanggal_izin_selesai'])->format('Y-m-d'),
                'kelengkapan' => $kelengkapan,
                'keperluan' => $data['keperluan'],
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Mengubah Detail Izin',
                'data' => $place,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengubah Detail Izin' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateIzinApprove(array $data)
    {
        try {
            $item = Izin::findOrFail($data['id']);
            $tanggal_izin = Carbon::parse($item->tanggal_izin);
            $tanggal_izin_selesai = Carbon::parse($item->tanggal_izin_selesai);

            $izin = Izin::where('id', $data['id'])->update([
                'status' => 1,
            ]);

            if ($izin) {
                Jadwal::where('id_user', $item->id_user)
                    ->whereBetween('tanggal', [$tanggal_izin, $tanggal_izin_selesai])
                    ->delete();
            }
            $user = User::findOrFail($item->id_user);
            $hari_ini = date('y-m-d');
            $notification = Notification::create([
                'category' => 'Izin',
                'user_id' => $item->id_user,
                'title' => "Izin Approved",
                'detail' => "Halo $user->name, Pengajuan Izin Kamu Untuk tanggal $tanggal_izin sampai tanggal $tanggal_izin_selesai sudah di approve ya. Silahkan cek halaman Izin. Terima Kasih.",
                'tanggal' => $hari_ini
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Approve Izin',
                'data' => Izin::find($izin),
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Approve Izin' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateIzinReject(array $data)
    {
        try {
            $izin_user = Izin::findOrFail($data['id']);
            $izin = Izin::where('id', $data['id'])->update([
                'status' => 2,
            ]);
            $user = User::findOrFail($izin_user->id_user);
            $hari_ini = date('y-m-d');
            $notification = Notification::create([
                'category' => 'Izin',
                'user_id' => $izin_user->id_user,
                'title' => "Izin Reject",
                'detail' => "Halo $user->name, Pengajuan Izin Kamu Untuk tanggal $izin_user->tanggal_izin sampai tanggal $izin_user->tanggal_izin_selesai ditolak. Silahkan cek halaman Izin. Terima Kasih.",
                'tanggal' => $hari_ini
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Reject Izin',
                'data' => Izin::find($izin),
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Reject Izin' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function deleteIzin(array $data)
    {
        try {
            $place = Izin::findOrFail($data['id']);
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
                'message' => 'Berhasil Menghapus Detail Izin',
                'data' => $place,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Menghapus Detail Izin - ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
