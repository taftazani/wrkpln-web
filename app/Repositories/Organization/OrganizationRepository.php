<?php

namespace App\Repositories\Organization;

use App\Models\Organization;
use App\Models\Role;
use App\Models\UserRole;
use App\Traits\ImageUpload;

use Exception;
use Illuminate\Support\Facades\DB;

class OrganizationRepository
{
    use ImageUpload;

    public function getOrganization()
    {
        try {
            $organization = Organization::orderByDesc('created_at')->get();
            return [
                'status' => true,
                'message' => 'Success Getting Data Organization',
                'data' => $organization,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Getting Data Organization' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function makeOrganization(array $data)
    {
        try {
            // Retrieve the last organization
            $lastOrganization = Organization::latest('id')->first();

            // Calculate the new ID (increment by 1)
            $newId = $lastOrganization ? $lastOrganization->id + 1 : 1;

            // Determine the length of the new ID and dynamically pad with zeros if necessary
            $length = max(3, strlen((string) $newId));
            $newCode = 'ORG' . str_pad($newId, $length, '0', STR_PAD_LEFT);

            $organization = Organization::create([
                'code' => $newCode,
                'name' => $data['name'],
                'detail' => $data['detail'],
                'status' => 1,
            ]);

            return [
                'status' => true,
                'message' => 'Success Creating Organization',
                'data' => $organization,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Creating Organization' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateOrganization(array $data)
    {
        try {
            $organization = Organization::findOrFail($data['id']);
            $organization->name = $data['name'];
            $organization->detail = $data['detail'];
            $organization->status = $data['status'];
            $organization->save();

            return [
                'status' => true,
                'message' => 'Success Changing Detail Organization',
                'data' => $organization,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Changing Detail Organization' . $e->getMessage(),
                'data' => null,
            ];
        }
    }



    public function deleteOrganization(array $data)
    {
        try {
            $organization = Organization::findOrFail($data['id']);
            // $user = UserRole::where('organization_id', $organization->id)->first();
            // if ($user) {
            //     return [
            //         'status' => false,
            //         'message' => 'Error Deleting Detail Organization - Masih Ada relasi User',
            //         'data' => $user,
            //     ];
            // }
            $organization->delete();

            return [
                'status' => true,
                'message' => 'Success Deleting Detail Organization',
                'data' => $organization,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Deleting Detail Organization - ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function bulkDeleteOrganizations(array $ids)
    {
        DB::beginTransaction();
        try {
            $deleted = Organization::whereIn('id', $ids)->delete();
            DB::commit();

            return [
                'status' => true,
                'message' => 'Success Deleting Organizations',
                'data' => $deleted,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'status' => false,
                'message' => 'Error Deleting Organizations - ' . $e->getMessage(),
                'data' => 0,
            ];
        }
    }

    public function bulkUpdateOrganizations(array $organizations)
    {
        DB::beginTransaction();
        try {
            foreach ($organizations as $data) {
                $organization = Organization::findOrFail($data['id']);
                if (isset($data['name'])) $organization->name = $data['name'];
                if (isset($data['detail'])) $organization->detail = $data['detail'];
                if (isset($data['status'])) $organization->status = $data['status'];
                $organization->save();
            }
            DB::commit();

            return [
                'status' => true,
                'message' => 'Success Updating Organizations',
                'data' => true,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'status' => false,
                'message' => 'Error Updating Organizations - ' . $e->getMessage(),
                'data' => false,

            ];
        }
    }
}
