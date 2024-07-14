<?php

namespace App\Repositories\Kasbon;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Lembur;
use App\Models\Kasbon;
use App\Models\Notification;
use App\Models\Shift;
use App\Models\User;
use App\Traits\ImageUpload;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Exception;

class KasbonRepository
{
    use ImageUpload;

    public function getKasbon()
    {
        try {
            $kasbon = Kasbon::orderByDesc('created_at')->with('user')->get();
            return [
                'status' => true,
                'message' => 'Berhasil Mengambil Data Kasbon',
                'data' => $kasbon,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Data Kasbon' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function getKasbonUser($filter)
    {
        try {
            $query = Kasbon::where('id_user', Auth::user()->id)->with('user')->orderByDesc('tanggal');

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

            $kasbon = $query->get();

            return [
                'status' => true,
                'message' => 'Berhasil Mengambil Data Kasbon Staff',
                'data' => $kasbon,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Data Kasbon Staff: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function makeKasbon(array $data)
    {
        try {
            $hari_ini = date('Y-m-d');
            $kasbon = Kasbon::create([
                'id_user' => Auth::user()->id,
                'cash_out' => $data['cash_out'],
                'keterangan' => $data['keterangan'],
                'tanggal' => $hari_ini,
                'status' => 0,
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Mengajukan Kasbon - Silahkan Menunggu Konfirmasi dari Manager / Owner',
                'data' => $kasbon,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengajukan Kasbon' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateKasbon(array $data)
    {
        try {
            $hari_ini = date('Y-m-d');
            $kasbon = Kasbon::where('id', $data['id'])->update([
                'id_user' => Auth::user()->id,
                'cash_out' => $data['cash_out'],
                'keterangan' => $data['keterangan'],
                'tanggal' => $hari_ini,
                'status' => 0,
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Mengubah Detail Kasbon',
                'data' => $kasbon,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengubah Detail Kasbon' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateKasbonApprove(array $data)
    {
        try {
            $item = Kasbon::findOrFail($data['id']);

            $kasbon = Kasbon::where('id', $data['id'])->update([
                'status' => 1,
            ]);
            $user = User::findOrFail($item->id_user);
            $hari_ini = date('y-m-d');
            $total = number_format($item->cash_out);
            $notification = Notification::create([
                'category' => 'Advance',
                'user_id' => $item->id_user,
                'title' => "Advance Approved",
                'detail' => "Halo $user->name, Pengajuan Advance Kamu sebesar Rp.$total sudah di approve ya. Silahkan cek halaman Advance / Kasbon. Terima Kasih.",
                'tanggal' => $hari_ini
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Approve Kasbon',
                'data' => Kasbon::find($kasbon),
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Approve Kasbon' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateKasbonReject(array $data)
    {
        try {
            $item = Kasbon::findOrFail($data['id']);

            $kasbon = Kasbon::where('id', $data['id'])->update([
                'status' => 2,
            ]);
            $user = User::findOrFail($item->id_user);
            $hari_ini = date('y-m-d');
            $total = number_format($item->cash_out);
            $notification = Notification::create([
                'category' => 'Advance',
                'user_id' => $item->id_user,
                'title' => "Advance Rejected",
                'detail' => "Halo $user->name, Pengajuan Advance Kamu sebesar Rp.$total ditolak. Silahkan cek halaman Advance / Kasbon. Terima Kasih.",
                'tanggal' => $hari_ini
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Reject Kasbon',
                'data' => Kasbon::find($kasbon),
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Reject Kasbon' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function deleteKasbon(array $data)
    {
        try {
            $kasbon = Kasbon::findOrFail($data['id']);

            $kasbon->delete();

            return [
                'status' => true,
                'message' => 'Berhasil Menghapus Detail Kasbon',
                'data' => $kasbon,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Menghapus Detail Kasbon - ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
