<?php

namespace App\Repositories\Gender;

use App\Models\Gender;
use App\Traits\ImageUpload;
use Exception;
use Illuminate\Support\Facades\DB;

class GenderRepository
{
        use ImageUpload;

        public function getGender()
        {
                try {
                        $function = Gender::orderByDesc('created_at')->get();
                        return [
                                'status' => true,
                                'message' => 'Success Getting Data Gender',
                                'data' => $function,
                        ];
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => 'Error Getting Data Gender' . $e->getMessage(),
                                'data' => null,
                        ];
                }
        }

        public function makeGender(array $data)
        {
                try {
                        // Retrieve the last function
                        $lastGender = Gender::latest('id')->first();

                        // Calculate the new ID (increment by 1)
                        $newId = $lastGender ? $lastGender->id + 1 : 1;

                        // Determine the length of the new ID and dynamically pad with zeros if necessary
                        $length = max(3, strlen((string) $newId));
                        $newCode = 'JK' . str_pad($newId, $length, '0', STR_PAD_LEFT);

                        $function = Gender::create([
                                'code' => $newCode,
                                'name' => $data['name'],
                                'status' => 1,
                        ]);

                        return [
                                'status' => true,
                                'message' => 'Success Creating Gender',
                                'data' => $function,
                        ];
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => 'Error Creating Gender' . $e->getMessage(),
                                'data' => null,
                        ];
                }
        }

        public function updateGender(array $data)
        {
                try {
                        $function = Gender::findOrFail($data['id']);
                        $function->name = $data['name'];
                        $function->status = $data['status'];
                        $function->save();

                        return [
                                'status' => true,
                                'message' => 'Success Changing Detail Gender',
                                'data' => $function,
                        ];
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => 'Error Changing Detail Gender' . $e->getMessage(),
                                'data' => null,
                        ];
                }
        }



        public function deleteGender(array $data)
        {
                try {
                        $function = Gender::findOrFail($data['id']);
                        // $user = UserRole::where('function_id', $function->id)->first();
                        // if ($user) {
                        //     return [
                        //         'status' => false,
                        //         'message' => 'Error Deleting Detail Gender - Masih Ada relasi User',
                        //         'data' => $user,
                        //     ];
                        // }
                        $function->delete();

                        return [
                                'status' => true,
                                'message' => 'Success Deleting Detail Gender',
                                'data' => $function,
                        ];
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => 'Error Deleting Detail Gender - ' . $e->getMessage(),
                                'data' => null,
                        ];
                }
        }
        public function bulkDeleteGenders(array $ids)
        {
                DB::beginTransaction();
                try {
                        $deleted = Gender::whereIn('id', $ids)->delete();
                        DB::commit();

                        return [
                                'status' => true,
                                'message' => 'Success Deleting Genders',
                                'data' => $deleted,
                        ];
                } catch (Exception $e) {
                        DB::rollBack();

                        return [
                                'status' => false,
                                'message' => 'Error Deleting Genders - ' . $e->getMessage(),
                                'data' => 0,
                        ];
                }
        }

        public function bulkUpdateGenders(array $functions)
        {
                DB::beginTransaction();
                try {
                        foreach ($functions as $data) {
                                $function = Gender::findOrFail($data['id']);
                                if (isset($data['name'])) $function->name = $data['name'];
                                if (isset($data['detail'])) $function->detail = $data['detail'];
                                if (isset($data['status'])) $function->status = $data['status'];
                                $function->save();
                        }
                        DB::commit();

                        return [
                                'status' => true,
                                'message' => 'Success Updating Genders',
                                'data' => true,
                        ];
                } catch (Exception $e) {
                        DB::rollBack();

                        return [
                                'status' => false,
                                'message' => 'Error Updating Genders - ' . $e->getMessage(),
                                'data' => false,

                        ];
                }
        }
}
