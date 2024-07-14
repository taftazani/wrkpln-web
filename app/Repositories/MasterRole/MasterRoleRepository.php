<?php

namespace App\Repositories\MasterRole;


use App\Models\Role;
use App\Models\UserRole;
use App\Traits\ImageUpload;

use Exception;

class MasterRoleRepository
{
    use ImageUpload;

    public function getMasterRole()
    {
        try {
            $role = Role::orderByDesc('created_at')->with('permissions')->get();
            return [
                'status' => true,
                'message' => 'Berhasil Mengambil Data MasterRole',
                'data' => $role,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengambil Data MasterRole' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function makeMasterRole(array $data)
    {
        try {
            $data['permission'] = json_decode($data['permission'], true);
            $role = Role::create([
                'name' => $data['name'],
            ]);
            foreach ($data['permission'] as $permission) {
                $role->permissions()->attach(intval($permission));
            }
            return [
                'status' => true,
                'message' => 'Berhasil Membuat MasterRole',
                'data' => $role,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Membuat MasterRole' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateMasterRole(array $data)
    {
        try {
            $data['permission'] = json_decode($data['permission'], true);

            $role = Role::findOrFail($data['id']);
            $role->name = $data['name'];
            $role->save();

            $role->permissions()->sync(array_map('intval', $data['permission']));
            return [
                'status' => true,
                'message' => 'Berhasil Mengubah Detail MasterRole',
                'data' => $role,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Mengubah Detail MasterRole' . $e->getMessage(),
                'data' => null,
            ];
        }
    }



    public function deleteMasterRole(array $data)
    {
        try {
            $role = Role::findOrFail($data['id']);
            $user = UserRole::where('role_id', $role->id)->first();
            if ($user) {
                return [
                    'status' => false,
                    'message' => 'Gagal Menghapus Detail MasterRole - Masih Ada relasi User',
                    'data' => $user,
                ];
            }
            $role->permissions()->detach();
            $role->delete();

            return [
                'status' => true,
                'message' => 'Berhasil Menghapus Detail MasterRole',
                'data' => $role,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal Menghapus Detail MasterRole - ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
