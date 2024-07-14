<?php

namespace App\Repositories\Absensi;

use App\Models\AbsenAttachment;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Lembur;
use App\Models\Place;
use App\Models\User;
use App\Traits\Absence;
use App\Traits\CheckLocation;
use App\Traits\ImageUpload;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Exception;

class AbsensiRepository
{
    use ImageUpload, CheckLocation, Absence;
    public function getAbsenAll()
    {
        try {
            $absens = Absensi::orderByDesc('tanggal')->with(['user', 'absen_attachment'])->get();
            return [
                'status' => true,
                'message' => 'Berhasil Mengambil Data Absen',
                'data' => $absens,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Data Absen' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function getAbsen()
    {
        try {
            $hari_ini = date('Y-m-d');

            $place = Absensi::where('id_user', Auth::user()->id)->where('tanggal', $hari_ini)->with('absen_attachment')->first();
            return [
                'status' => true,
                'message' => 'Berhasil Mengambil Data Absen',
                'data' => $place,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Data Absen' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function getAbsenUser($filter)
    {
        try {
            $query = Absensi::where('id_user', Auth::user()->id)->with('user')->orderByDesc('created_at');

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
                'message' => 'Berhasil Mengambil Data Absen Staff',
                'data' => $place,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Data Absen Staff: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function makeAbsenMasuk(array $data)
    {
        $today = date('Y-m-d');
        $user = User::where('id', Auth::user()->id)->with('area')->first();
        $jadwal = Jadwal::where('id_user', Auth::user()->id)
            ->where('tanggal', $today)
            ->with(['place', 'shift'])
            ->first();

        if (!$user) {
            return [
                'status' => false,
                'message' => 'Area user not found.',
                'data' => null,
            ];
        }

        if (!$jadwal) {
            return [
                'status' => false,
                'message' => 'Jadwal not found for today.',
                'data' => null,
            ];
        }

        $clockInTime = Carbon::parse($jadwal->shift->clock_in);
        $isInRadius = $this->checkLocation($data['lat1'], $data['lon1'], $user);
        $avatarName = $this->uploadImage($data['pict'], 'absen');

        if ($isInRadius) {
            return $this->saveAbsenMasuk($jadwal, $data['clock_in'], $clockInTime, $avatarName, $data['keterangan']);
        } else {
            return [
                'status' => false,
                'message' => 'Location does not match.',
                'data' => $isInRadius,
            ];
        }
    }

    public function makeAbsenKeluar(array $data)
    {
        $absen = Absensi::find($data['absen_id']);
        $user = User::where('id', Auth::user()->id)->with('area')->first();
        $jadwal = Jadwal::where('id_user', Auth::user()->id)->where('tanggal', $absen->tanggal)->with(['place', 'shift'])->orderBy('tanggal', 'desc')->first();

        $work_hour = intval($jadwal->place->work_hour);

        if (!$jadwal) {
            return [
                'status' => false,
                'message' => 'Jadwal not found for today.',
                'data' => null,
            ];
        }

        $clockOutTime = $jadwal->shift->clock_out;
        $clockInTime = $jadwal->shift->clock_in;
        $isInRadius = $this->checkLocation($data['lat1'], $data['lon1'], $user);
        $proofOfOvertime = $data['pict'] ? $this->uploadImage($data['pict'], 'absen') : null;
        // $proofOfOvertime =  null;

        if ($isInRadius) {
            return $this->saveAbsenKeluar($absen, $jadwal, $data['clock_out'], date('Y-m-d'), $clockInTime, $clockOutTime,  $data['keterangan'], $proofOfOvertime, $work_hour, implode(", ", $isInRadius));
        } else {
            return [
                'status' => false,
                'message' => 'Location does not match.',
                'data' => null,
            ];
        }
    }
    public function makeAbsenAttachment(array $data)
    {
        $file = $data['pict'] ? $this->uploadImage($data['pict'], 'absen_attachment') : null;
        try {
            $attachment = AbsenAttachment::create([
                'pict' => $file,
                'detail' => $data['detail'],
            ]);
            $attachment->absensi()->attach(intval($data['absen_id']));
            // $attachment = Absensi::where('id', 21)->with('absen_attachment')->first();
            return [
                'status' => true,
                'message' => 'Success Add Attachment to Absen',
                'data' => $attachment,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function removeAbsenAttachment(array $data)
    {
        try {
            // Find the attachment by its ID
            $attachment = AbsenAttachment::find($data['attachment_id']);

            if (!$attachment) {
                return [
                    'status' => false,
                    'message' => 'Attachment not found',
                    'data' => null,
                ];
            }

            // Get the file path
            $filePath = $attachment->pict;
            $proofOfOvertime = $this->deleteImage($filePath);

            // Detach the attachment from the related Absensi record
            $attachment->absensi()->detach();

            // Delete the attachment record
            $attachment->delete();

            return [
                'status' => true,
                'message' => 'Attachment deleted successfully',
                'data' => null,
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
