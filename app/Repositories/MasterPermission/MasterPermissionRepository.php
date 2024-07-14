<?php

namespace App\Repositories\MasterPermission;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\UserRole;
use App\Traits\ImageUpload;

use Exception;

class MasterPermissionRepository
{
    use ImageUpload;

    public function getMasterPermission()
    {
        try {
            $permission = Permission::orderByDesc('created_at')->get();
            return [
                'status' => true,
                'message' => 'Berhasil Mengambil Data MasterPermission',
                'data' => $permission,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Data MasterPermission' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function makeMasterPermission(array $data)
    {
        try {
            $name = strtolower(str_replace(' ', '_', $data['name']));

            $permission = Permission::create([
                'name' => $name,
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Membuat MasterPermission',
                'data' => $permission,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Membuat MasterPermission' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateMasterPermission(array $data)
    {
        try {
            $name = strtolower(str_replace(' ', '_', $data['name']));

            $permission = Permission::where('id', $data['id'])->update([
                'name' => $name,
            ]);
            return [
                'status' => true,
                'message' => 'Berhasil Mengubah Detail MasterPermission',
                'data' => $permission,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengubah Detail MasterPermission' . $e->getMessage(),
                'data' => null,
            ];
        }
    }



    public function deleteMasterPermission(array $data)
    {
        try {
            $permission = Permission::findOrFail($data['id']);
            $permission_role = RolePermission::where('permission_id', $permission->id)->first();
            if ($permission_role) {
                return [
                    'status' => false,
                    'message' => 'Gagal Menghapus Detail MasterPermission - Masih Ada relasi Role',
                    'data' => $permission_role,
                ];
            }
            $permission->roles()->detach();
            $permission->delete();

            return [
                'status' => true,
                'message' => 'Berhasil Menghapus Detail MasterPermission',
                'data' => $permission,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Menghapus Detail MasterPermission - ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
