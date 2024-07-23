<?php

namespace App\Repositories\MenuMapping;

use App\Models\MenuMapping;
use App\Traits\ImageUpload;
use Exception;
use Illuminate\Support\Facades\DB;

class MenuMappingRepository
{
        use ImageUpload;

        public function getMenuMapping()
        {
                try {
                        $menumapping = MenuMapping::orderByDesc('created_at')->get();
                        return [
                                'status' => true,
                                'message' => 'Success Getting Data MenuMapping',
                                'data' => $menumapping,
                        ];
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => 'Error Getting Data MenuMapping' . $e->getMessage(),
                                'data' => null,
                        ];
                }
        }

        public function makeMenuMapping(array $data)
        {
                try {
                        $menumapping = MenuMapping::create([
                                'package_type_id' => $data['package_type_id'],
                                'module' => $data['module'],
                                'permission_id' => $data['permission_id'],
                                'status' => $data['status'],
                        ]);

                        return [
                                'status' => true,
                                'message' => 'Success Creating MenuMapping',
                                'data' => $menumapping,
                        ];
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => 'Error Creating MenuMapping' . $e->getMessage(),
                                'data' => null,
                        ];
                }
        }

        public function updateMenuMapping(array $data)
        {
                try {
                        $menumapping = MenuMapping::findOrFail($data['id']);
                        $menumapping->package_type_id = $data['package_type_id'];
                        $menumapping->module = $data['module'];
                        $menumapping->permission_id = $data['permission_id'];
                        $menumapping->status = $data['status'];
                        $menumapping->save();

                        return [
                                'status' => true,
                                'message' => 'Success Changing Detail MenuMapping',
                                'data' => $menumapping,
                        ];
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => 'Error Changing Detail MenuMapping' . $e->getMessage(),
                                'data' => null,
                        ];
                }
        }



        public function deleteMenuMapping(array $data)
        {
                try {
                        $menumapping = MenuMapping::findOrFail($data['id']);
                        // $user = UserRole::where('function_id', $menumapping->id)->first();
                        // if ($user) {
                        //     return [
                        //         'status' => false,
                        //         'message' => 'Error Deleting Detail MenuMapping - Masih Ada relasi User',
                        //         'data' => $user,
                        //     ];
                        // }
                        $menumapping->delete();

                        return [
                                'status' => true,
                                'message' => 'Success Deleting Detail MenuMapping',
                                'data' => $menumapping,
                        ];
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => 'Error Deleting Detail MenuMapping - ' . $e->getMessage(),
                                'data' => null,
                        ];
                }
        }
        public function bulkDeleteMenuMappings(array $ids)
        {
                DB::beginTransaction();
                try {
                        $deleted = MenuMapping::whereIn('id', $ids)->delete();
                        DB::commit();

                        return [
                                'status' => true,
                                'message' => 'Success Deleting MenuMappings',
                                'data' => $deleted,
                        ];
                } catch (Exception $e) {
                        DB::rollBack();

                        return [
                                'status' => false,
                                'message' => 'Error Deleting MenuMappings - ' . $e->getMessage(),
                                'data' => 0,
                        ];
                }
        }

        public function bulkUpdateMenuMappings(array $menumappings)
        {
                DB::beginTransaction();
                try {
                        foreach ($menumappings as $data) {
                                $menumapping = MenuMapping::findOrFail($data['id']);
                                if (isset($data['name'])) $menumapping->name = $data['name'];
                                if (isset($data['detail'])) $menumapping->detail = $data['detail'];
                                if (isset($data['status'])) $menumapping->status = $data['status'];
                                $menumapping->save();
                        }
                        DB::commit();

                        return [
                                'status' => true,
                                'message' => 'Success Updating MenuMappings',
                                'data' => true,
                        ];
                } catch (Exception $e) {
                        DB::rollBack();

                        return [
                                'status' => false,
                                'message' => 'Error Updating MenuMappings - ' . $e->getMessage(),
                                'data' => false,

                        ];
                }
        }
}
