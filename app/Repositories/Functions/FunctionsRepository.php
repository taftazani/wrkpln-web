<?php

namespace App\Repositories\Functions;

use App\Models\Functions;
use App\Traits\ImageUpload;

use Exception;
use Illuminate\Support\Facades\DB;

class FunctionsRepository
{
    use ImageUpload;

    public function getFunctions()
    {
        try {
            $organization = Functions::orderByDesc('created_at')->get();
            return [
                'status' => true,
                'message' => 'Success Getting Data Functions',
                'data' => $organization,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Getting Data Functions' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function makeFunctions(array $data)
    {
        try {
            // Retrieve the last organization
            $lastFunctions = Functions::latest('id')->first();

            // Calculate the new ID (increment by 1)
            $newId = $lastFunctions ? $lastFunctions->id + 1 : 1;

            // Determine the length of the new ID and dynamically pad with zeros if necessary
            $length = max(3, strlen((string) $newId));
            $newCode = 'FN' . str_pad($newId, $length, '0', STR_PAD_LEFT);

            $organization = Functions::create([
                'code' => $newCode,
                'name' => $data['name'],
                'detail' => $data['detail'],
                'status' => 1,
            ]);

            return [
                'status' => true,
                'message' => 'Success Creating Functions',
                'data' => $organization,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Creating Functions' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateFunctions(array $data)
    {
        try {
            $organization = Functions::findOrFail($data['id']);
            $organization->name = $data['name'];
            $organization->detail = $data['detail'];
            $organization->status = $data['status'];
            $organization->save();

            return [
                'status' => true,
                'message' => 'Success Changing Detail Functions',
                'data' => $organization,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Changing Detail Functions' . $e->getMessage(),
                'data' => null,
            ];
        }
    }



    public function deleteFunctions(array $data)
    {
        try {
            $organization = Functions::findOrFail($data['id']);
            // $user = UserRole::where('organization_id', $organization->id)->first();
            // if ($user) {
            //     return [
            //         'status' => false,
            //         'message' => 'Error Deleting Detail Functions - Masih Ada relasi User',
            //         'data' => $user,
            //     ];
            // }
            $organization->delete();

            return [
                'status' => true,
                'message' => 'Success Deleting Detail Functions',
                'data' => $organization,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Deleting Detail Functions - ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function bulkDeleteFunctionss(array $ids)
    {
        DB::beginTransaction();
        try {
            $deleted = Functions::whereIn('id', $ids)->delete();
            DB::commit();

            return [
                'status' => true,
                'message' => 'Success Deleting Functionss',
                'data' => $deleted,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'status' => false,
                'message' => 'Error Deleting Functionss - ' . $e->getMessage(),
                'data' => 0,
            ];
        }
    }

    public function bulkUpdateFunctionss(array $organizations)
    {
        DB::beginTransaction();
        try {
            foreach ($organizations as $data) {
                $organization = Functions::findOrFail($data['id']);
                if (isset($data['name'])) $organization->name = $data['name'];
                if (isset($data['detail'])) $organization->detail = $data['detail'];
                if (isset($data['status'])) $organization->status = $data['status'];
                $organization->save();
            }
            DB::commit();

            return [
                'status' => true,
                'message' => 'Success Updating Functionss',
                'data' => true,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'status' => false,
                'message' => 'Error Updating Functionss - ' . $e->getMessage(),
                'data' => false,

            ];
        }
    }
}
