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
            $function = Functions::orderByDesc('created_at')->get();
            return [
                'status' => true,
                'message' => 'Success Getting Data Functions',
                'data' => $function,
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
            // Retrieve the last function
            $lastFunctions = Functions::latest('id')->first();

            // Calculate the new ID (increment by 1)
            $newId = $lastFunctions ? $lastFunctions->id + 1 : 1;

            // Determine the length of the new ID and dynamically pad with zeros if necessary
            $length = max(3, strlen((string) $newId));
            $newCode = 'FN' . str_pad($newId, $length, '0', STR_PAD_LEFT);

            $function = Functions::create([
                'code' => $newCode,
                'name' => $data['name'],
                'detail' => $data['detail'],
                'status' => 1,
            ]);

            return [
                'status' => true,
                'message' => 'Success Creating Functions',
                'data' => $function,
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
            $function = Functions::findOrFail($data['id']);
            $function->name = $data['name'];
            $function->detail = $data['detail'];
            $function->status = $data['status'];
            $function->save();

            return [
                'status' => true,
                'message' => 'Success Changing Detail Functions',
                'data' => $function,
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
            $function = Functions::findOrFail($data['id']);
            // $user = UserRole::where('function_id', $function->id)->first();
            // if ($user) {
            //     return [
            //         'status' => false,
            //         'message' => 'Error Deleting Detail Functions - Masih Ada relasi User',
            //         'data' => $user,
            //     ];
            // }
            $function->delete();

            return [
                'status' => true,
                'message' => 'Success Deleting Detail Functions',
                'data' => $function,
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

    public function bulkUpdateFunctionss(array $functions)
    {
        DB::beginTransaction();
        try {
            foreach ($functions as $data) {
                $function = Functions::findOrFail($data['id']);
                if (isset($data['name'])) $function->name = $data['name'];
                if (isset($data['detail'])) $function->detail = $data['detail'];
                if (isset($data['status'])) $function->status = $data['status'];
                $function->save();
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
