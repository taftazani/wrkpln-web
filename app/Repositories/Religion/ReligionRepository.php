<?php

namespace App\Repositories\Religion;

use App\Models\Religion;
use App\Traits\ImageUpload;
use Exception;
use Illuminate\Support\Facades\DB;

class ReligionRepository
{
        use ImageUpload;

        public function getReligion()
        {
                try {
                        $religion = Religion::orderByDesc('created_at')->get();
                        return [
                                'status' => true,
                                'message' => 'Success Getting Data Religion',
                                'data' => $religion,
                        ];
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => 'Error Getting Data Religion' . $e->getMessage(),
                                'data' => null,
                        ];
                }
        }

        public function makeReligion(array $data)
        {
                try {
                        // Retrieve the last function
                        $lastReligion = Religion::latest('id')->first();

                        // Calculate the new ID (increment by 1)
                        $newId = $lastReligion ? $lastReligion->id + 1 : 1;

                        // Determine the length of the new ID and dynamically pad with zeros if necessary
                        $length = max(3, strlen((string) $newId));
                        $newCode = 'AGM' . str_pad($newId, $length, '0', STR_PAD_LEFT);

                        $religion = Religion::create([
                                'code' => $newCode,
                                'name' => $data['name'],
                                'status' => 1,
                        ]);

                        return [
                                'status' => true,
                                'message' => 'Success Creating Religion',
                                'data' => $religion,
                        ];
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => 'Error Creating Religion' . $e->getMessage(),
                                'data' => null,
                        ];
                }
        }

        public function updateReligion(array $data)
        {
                try {
                        $religion = Religion::findOrFail($data['id']);
                        $religion->name = $data['name'];
                        $religion->status = $data['status'];
                        $religion->save();

                        return [
                                'status' => true,
                                'message' => 'Success Changing Detail Religion',
                                'data' => $religion,
                        ];
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => 'Error Changing Detail Religion' . $e->getMessage(),
                                'data' => null,
                        ];
                }
        }



        public function deleteReligion(array $data)
        {
                try {
                        $religion = Religion::findOrFail($data['id']);

                        $religion->delete();

                        return [
                                'status' => true,
                                'message' => 'Success Deleting Detail Religion',
                                'data' => $religion,
                        ];
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => 'Error Deleting Detail Religion - ' . $e->getMessage(),
                                'data' => null,
                        ];
                }
        }
        public function bulkDeleteReligions(array $ids)
        {
                DB::beginTransaction();
                try {
                        $deleted = Religion::whereIn('id', $ids)->delete();
                        DB::commit();

                        return [
                                'status' => true,
                                'message' => 'Success Deleting Religions',
                                'data' => $deleted,
                        ];
                } catch (Exception $e) {
                        DB::rollBack();

                        return [
                                'status' => false,
                                'message' => 'Error Deleting Religions - ' . $e->getMessage(),
                                'data' => 0,
                        ];
                }
        }

        public function bulkUpdateReligions(array $religions)
        {
                DB::beginTransaction();
                try {
                        foreach ($religions as $data) {
                                $religion = Religion::findOrFail($data['id']);
                                if (isset($data['name'])) $religion->name = $data['name'];
                                if (isset($data['status'])) $religion->status = $data['status'];
                                $religion->save();
                        }
                        DB::commit();

                        return [
                                'status' => true,
                                'message' => 'Success Updating Religions',
                                'data' => true,
                        ];
                } catch (Exception $e) {
                        DB::rollBack();

                        return [
                                'status' => false,
                                'message' => 'Error Updating Religions - ' . $e->getMessage(),
                                'data' => false,

                        ];
                }
        }
}
