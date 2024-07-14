<?php

namespace App\Repositories\MasterNotification;

use App\Models\Notification;
use App\Models\Role;
use App\Models\RoleNotification;
use App\Models\UserRole;
use App\Traits\ImageUpload;

use Exception;
use Illuminate\Support\Facades\Auth;

class MasterNotificationRepository
{
    use ImageUpload;

    public function getMasterNotification()
    {
        try {
            $notification = Notification::orderByDesc('created_at')->get();
            return [
                'status' => true,
                'message' => 'Berhasil Mengambil Data MasterNotification',
                'data' => $notification,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Data MasterNotification' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function getMasterNotificationUser()
    {
        try {
            $notification = Notification::where('user_id', Auth::user()->id)->orWhere('user_id', NULL)->orderByDesc('created_at')->get();
            return [
                'status' => true,
                'message' => 'Berhasil Mengambil Data MasterNotification',
                'data' => $notification,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Data MasterNotification' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function makeMasterNotification(array $data)
    {
        try {
            if (empty($data['user_id'])) {
                $notification = Notification::create([
                    'category' => $data['category'],
                    'title' => $data['title'],
                    'detail' => $data['detail'],
                    'tanggal' => $data['tanggal']
                ]);
                return [
                    'status' => true,
                    'message' => 'Berhasil Membuat MasterNotification',
                    'data' => $notification,
                ];
            }
            $notification = Notification::create([
                'category' => $data['category'],
                'user_id' => $data['user_id'],
                'title' => $data['title'],
                'detail' => $data['detail'],
                'tanggal' => $data['tanggal']
            ]);

            return [
                'status' => true,
                'message' => 'Berhasil Membuat MasterNotification',
                'data' => $notification,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Membuat MasterNotification' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateMasterNotification(array $data)
    {
        try {
            // $name = strtolower(str_replace(' ', '_', $data['name']));

            // $notification = Notification::where('id', $data['id'])->update([
            //     'name' => $name,
            // ]);
            return [
                'status' => true,
                'message' => 'Berhasil Mengubah Detail MasterNotification',
                'data' => true,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengubah Detail MasterNotification' . $e->getMessage(),
                'data' => null,
            ];
        }
    }



    public function deleteMasterNotification(array $data)
    {
        try {
            $notification = Notification::findOrFail($data['id']);
            $notification->delete();
            return [
                'status' => true,
                'message' => 'Berhasil Menghapus Detail MasterNotification',
                'data' => $notification,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Menghapus Detail MasterNotification - ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function temporaryDeleteMasterNotification(array $data)
    {
        try {
            $notification = Notification::findOrFail($data['id']);
            $notification->status = 0;
            $notification->save();
            return [
                'status' => true,
                'message' => 'Berhasil Menghapus Detail MasterNotification',
                'data' => $notification,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Menghapus Detail MasterNotification - ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
