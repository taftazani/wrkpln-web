<?php

namespace App\Repositories\Citizen;

use App\Models\Citizen;
use App\Traits\ImageUpload;
use Exception;
use Illuminate\Support\Facades\DB;

class CitizenRepository
{
        use ImageUpload;

        public function getCitizen()
        {
                try {
                        $religion = Citizen::orderByDesc('created_at')->get();
                        return [
                                'status' => true,
                                'message' => 'Success Getting Data Citizen',
                                'data' => $religion,
                        ];
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => 'Error Getting Data Citizen' . $e->getMessage(),
                                'data' => null,
                        ];
                }
        }

        public function makeCitizen(array $data)
        {
                try {
                        // Retrieve the last function
                        $lastCitizen = Citizen::latest('id')->first();

                        // Calculate the new ID (increment by 1)
                        $newId = $lastCitizen ? $lastCitizen->id + 1 : 1;

                        // Determine the length of the new ID and dynamically pad with zeros if necessary
                        $length = max(3, strlen((string) $newId));
                        $newCode = 'KWG' . str_pad($newId, $length, '0', STR_PAD_LEFT);

                        $religion = Citizen::create([
                                'code' => $newCode,
                                'name' => $data['name'],
                                'status' => $data['status'],
                        ]);

                        return [
                                'status' => true,
                                'message' => 'Success Creating Citizen',
                                'data' => $religion,
                        ];
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => 'Error Creating Citizen' . $e->getMessage(),
                                'data' => null,
                        ];
                }
        }

        public function updateCitizen(array $data)
        {
                try {
                        $religion = Citizen::findOrFail($data['id']);
                        $religion->name = $data['name'];
                        $religion->status = $data['status'];
                        $religion->save();

                        return [
                                'status' => true,
                                'message' => 'Success Changing Detail Citizen',
                                'data' => $religion,
                        ];
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => 'Error Changing Detail Citizen' . $e->getMessage(),
                                'data' => null,
                        ];
                }
        }



        public function deleteCitizen(array $data)
        {
                try {
                        $religion = Citizen::findOrFail($data['id']);

                        $religion->delete();

                        return [
                                'status' => true,
                                'message' => 'Success Deleting Detail Citizen',
                                'data' => $religion,
                        ];
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => 'Error Deleting Detail Citizen - ' . $e->getMessage(),
                                'data' => null,
                        ];
                }
        }
        public function bulkDeleteCitizens(array $ids)
        {
                DB::beginTransaction();
                try {
                        $deleted = Citizen::whereIn('id', $ids)->delete();
                        DB::commit();

                        return [
                                'status' => true,
                                'message' => 'Success Deleting Citizens',
                                'data' => $deleted,
                        ];
                } catch (Exception $e) {
                        DB::rollBack();

                        return [
                                'status' => false,
                                'message' => 'Error Deleting Citizens - ' . $e->getMessage(),
                                'data' => 0,
                        ];
                }
        }

        public function bulkUpdateCitizens(array $religions)
        {
                DB::beginTransaction();
                try {
                        foreach ($religions as $data) {
                                $religion = Citizen::findOrFail($data['id']);
                                if (isset($data['name'])) $religion->name = $data['name'];
                                if (isset($data['status'])) $religion->status = $data['status'];
                                $religion->save();
                        }
                        DB::commit();

                        return [
                                'status' => true,
                                'message' => 'Success Updating Citizens',
                                'data' => true,
                        ];
                } catch (Exception $e) {
                        DB::rollBack();

                        return [
                                'status' => false,
                                'message' => 'Error Updating Citizens - ' . $e->getMessage(),
                                'data' => false,

                        ];
                }
        }
}
