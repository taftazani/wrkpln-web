<?php

namespace App\Repositories\MarriedStatus;

use App\Models\MarriedStatus;
use App\Traits\ImageUpload;
use Exception;
use Illuminate\Support\Facades\DB;

class MarriedStatusRepository
{
        use ImageUpload;

        public function getMarriedStatus()
        {
                try {
                        $function = MarriedStatus::orderByDesc('created_at')->get();
                        return [
                                'status' => true,
                                'message' => 'Success Getting Data MarriedStatus',
                                'data' => $function,
                        ];
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => 'Error Getting Data MarriedStatus' . $e->getMessage(),
                                'data' => null,
                        ];
                }
        }

        public function makeMarriedStatus(array $data)
        {
                try {
                        // Retrieve the last function
                        $lastMarriedStatus = MarriedStatus::latest('id')->first();

                        // Calculate the new ID (increment by 1)
                        $newId = $lastMarriedStatus ? $lastMarriedStatus->id + 1 : 1;

                        // Determine the length of the new ID and dynamically pad with zeros if necessary
                        $length = max(3, strlen((string) $newId));
                        $newCode = 'MS' . str_pad($newId, $length, '0', STR_PAD_LEFT);

                        $function = MarriedStatus::create([
                                'code' => $newCode,
                                'name' => $data['name'],
                                'status' => $data['status'],
                        ]);

                        return [
                                'status' => true,
                                'message' => 'Success Creating MarriedStatus',
                                'data' => $function,
                        ];
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => 'Error Creating MarriedStatus' . $e->getMessage(),
                                'data' => null,
                        ];
                }
        }

        public function updateMarriedStatus(array $data)
        {
                try {
                        $function = MarriedStatus::findOrFail($data['id']);
                        $function->name = $data['name'];
                        $function->status = $data['status'];
                        $function->save();

                        return [
                                'status' => true,
                                'message' => 'Success Changing Detail MarriedStatus',
                                'data' => $function,
                        ];
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => 'Error Changing Detail MarriedStatus' . $e->getMessage(),
                                'data' => null,
                        ];
                }
        }



        public function deleteMarriedStatus(array $data)
        {
                try {
                        $function = MarriedStatus::findOrFail($data['id']);
                        // $user = UserRole::where('function_id', $function->id)->first();
                        // if ($user) {
                        //     return [
                        //         'status' => false,
                        //         'message' => 'Error Deleting Detail MarriedStatus - Masih Ada relasi User',
                        //         'data' => $user,
                        //     ];
                        // }
                        $function->delete();

                        return [
                                'status' => true,
                                'message' => 'Success Deleting Detail MarriedStatus',
                                'data' => $function,
                        ];
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => 'Error Deleting Detail MarriedStatus - ' . $e->getMessage(),
                                'data' => null,
                        ];
                }
        }
        public function bulkDeleteMarriedStatuss(array $ids)
        {
                DB::beginTransaction();
                try {
                        $deleted = MarriedStatus::whereIn('id', $ids)->delete();
                        DB::commit();

                        return [
                                'status' => true,
                                'message' => 'Success Deleting MarriedStatuss',
                                'data' => $deleted,
                        ];
                } catch (Exception $e) {
                        DB::rollBack();

                        return [
                                'status' => false,
                                'message' => 'Error Deleting MarriedStatuss - ' . $e->getMessage(),
                                'data' => 0,
                        ];
                }
        }

        public function bulkUpdateMarriedStatuss(array $functions)
        {
                DB::beginTransaction();
                try {
                        foreach ($functions as $data) {
                                $function = MarriedStatus::findOrFail($data['id']);
                                if (isset($data['name'])) $function->name = $data['name'];
                                if (isset($data['detail'])) $function->detail = $data['detail'];
                                if (isset($data['status'])) $function->status = $data['status'];
                                $function->save();
                        }
                        DB::commit();

                        return [
                                'status' => true,
                                'message' => 'Success Updating MarriedStatuss',
                                'data' => true,
                        ];
                } catch (Exception $e) {
                        DB::rollBack();

                        return [
                                'status' => false,
                                'message' => 'Error Updating MarriedStatuss - ' . $e->getMessage(),
                                'data' => false,

                        ];
                }
        }
}
