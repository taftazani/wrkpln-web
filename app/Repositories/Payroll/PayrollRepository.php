<?php

namespace App\Repositories\Payroll;

use Exception;
use Carbon\Carbon;
use App\Models\Shift;
use App\Models\Jadwal;
use App\Models\Lembur;
use App\Models\Absensi;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Payroll;
use App\Models\User;
use App\Traits\ImageUpload;
use Illuminate\Support\Facades\Auth;

class PayrollRepository
{
    use ImageUpload;

    public function getPayroll($userId, $start_date, $end_date)
    {
        try {
            // $dates = explode(',', $dateRange);
            // if (!empty($dateRange)) {
            //     $dates = explode(',', $dateRange);
            // }
            $userId = intval($userId);
            $query = Payment::where('id_user', $userId)->with('user');
            if (empty($end_date) || empty($start_date)) {
                $query->orderByDesc('tanggal');
            } else {
                $startDate = Carbon::parse($start_date);
                $endDate = Carbon::parse($end_date);
                $query->whereBetween('tanggal', [$start_date, $end_date]);
            }
            $payroll = $query->get();
            return [
                'status' => true,
                'message' => 'Berhasil Mengambil Data Payroll',
                'data' => $payroll,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Data Payroll' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function getPayrollUser($filter)
    {
        try {
            $query = Payment::where('id_user', Auth::user()->id)->with('user')->orderByDesc('tanggal');

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

            $payroll = $query->get();

            return [
                'status' => true,
                'message' => 'Berhasil Mengambil Data Payroll Staff',
                'data' => $payroll,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Data Payroll Staff: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function makePayroll(array $data)
    {
        try {
            $hari_ini = date('Y-m-d');
            $payroll = Payment::create([
                'id_user' => Auth::user()->id,
                'cash_out' => $data['cash_out'],
                'keterangan' => $data['keterangan'],
                'tanggal' => $hari_ini,
                'status' => 0,
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Mengajukan Payroll - Silahkan Menunggu Konfirmasi dari Manager / Owner',
                'data' => $payroll,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengajukan Payroll' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updatePayrollBulk(array $data)
    {
        try {
            $dari = Carbon::parse($data['tanggal_dari']);
            $sampai = Carbon::parse($data['tanggal_sampai']);
            $payroll = Payment::where('id_user', $data['id_user'])->whereBetween('tanggal', [$dari, $sampai])->update([
                'status' => 1,
            ]);
            $user = User::findOrFail($data['id_user']);
            $hari_ini = date('y-m-d');
            $notification = Notification::create([
                'category' => 'Payment',
                'user_id' => $user->id,
                'title' => "Payment Transfered",
                'detail' => "Halo $user->name, Gaji Kamu Untuk Periode $dari sampai $sampai sudah di transfer ya. Silahkan cek halaman Payment dan Rekening kamu. Terima Kasih.",
                'tanggal' => $hari_ini
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Mengubah Detail Payroll',
                'data' => $payroll,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengubah Detail Payroll' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function updatePayrollOne(array $data)
    {
        try {
            $payroll = Payment::where('id', $data['id'])->update([
                'cash_out' => $data['cash_out'],
                'status' => 1,
            ]);
            $item = Payment::findOrFail($data['id']);
            $user = User::findOrFail($item->id_user);
            $hari_ini = date('y-m-d');
            $notification = Notification::create([
                'category' => 'Payment',
                'user_id' => $user->id,
                'title' => "Payment Transfered",
                'detail' => "Halo $user->name, Gaji Kamu Untuk Periode $item->tanggal sudah di transfer ya. Silahkan cek halaman Payment dan Rekening kamu. Terima Kasih.",
                'tanggal' => $hari_ini
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Mengubah Detail Payroll',
                'data' => $payroll,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengubah Detail Payroll' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function updatePayroll(array $data)
    {
        try {
            $hari_ini = date('Y-m-d');
            $payroll = Payment::where('id', $data['id'])->update([
                'id_user' => Auth::user()->id,
                'cash_out' => $data['cash_out'],
                'keterangan' => $data['keterangan'],
                'tanggal' => $hari_ini,
                'status' => 0,
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Mengubah Detail Payroll',
                'data' => $payroll,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengubah Detail Payroll' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updatePayrollApprove(array $data)
    {
        try {
            $payroll = Payment::where('id', $data['id'])->update([
                'status' => 1,
                'cash_out' => $data['cash_out'],

            ]);

            return [
                'status' => true,
                'message' => 'Berhasil Transfer Gaji',
                'data' => Payment::find($payroll),
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Transfer Gaji' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updatePayrollReject(array $data)
    {
        try {
            $payroll = Payment::where('id', $data['id'])->update([
                'status' => 2,
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Reject Payroll',
                'data' => Payment::find($payroll),
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Reject Payroll' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function deletePayroll(array $data)
    {
        try {
            $payroll = Payment::findOrFail($data['id']);

            $payroll->delete();

            return [
                'status' => true,
                'message' => 'Berhasil Menghapus Detail Payroll',
                'data' => $payroll,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Menghapus Detail Payroll - ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
