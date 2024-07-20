<?php

namespace App\Services\MenuMapping;

use App\Repositories\MenuMapping\MenuMappingRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuMappingService
{
        public function __construct(private MenuMappingRepository $repository)
        {
        }

        public function get()
        {
                try {
                        return $this->repository->getMenuMapping();
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => $e->getMessage(),
                                'data' => null,
                        ];
                }
        }

        public function make(Request $request)
        {
                $validator = Validator::make($request->all(), [
                        'package_type_id' => 'required',
                        'module' => 'required|in:web,mobile',
                        'permission_id' => 'required',
                ]);

                if ($validator->fails()) {
                        return [
                                'status' => false,
                                'message' => 'Validation Error',
                                'data' => $validator->errors(),
                        ];
                }

                try {
                        return $this->repository->makeMenuMapping($request->all());
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => $e->getMessage(),
                                'data' => null,
                        ];
                }
        }

        public function update(Request $request)
        {
                $validator = Validator::make($request->all(), [
                        'id' => 'required',
                        'package_type_id' => 'required',
                        'module' => 'required',
                        'permission_id' => 'required',
                        'status' => 'required'
                ]);

                if ($validator->fails()) {
                        return [
                                'status' => false,
                                'message' => 'Validation Error',
                                'data' => $validator->errors(),
                        ];
                }

                try {
                        return $this->repository->updateMenuMapping($request->all());
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => $e->getMessage(),
                                'data' => null,
                        ];
                }
        }
        public function bulkUpdate(Request $request)
        {
                $validator = Validator::make($request->all(), [
                        'menu_mappings' => 'required|array',
                        'menu_mappings.*.id' => 'required|exists:menu_mappings,id',
                        'menu_mappings.*.name' => 'required|string|max:255',
                        'menu_mappings.*.detail' => 'required|string|max:255',
                        'menu_mappings.*.status' => 'required|integer|in:0,1',
                ]);


                if ($validator->fails()) {
                        return [
                                'status' => false,
                                'message' => 'Validation Error',
                                'data' => $validator->errors(),
                        ];
                }

                try {
                        return $this->repository->bulkUpdateMenuMappings($request->all());
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => $e->getMessage(),
                                'data' => null,
                        ];
                }
        }

        public function delete(Request $request)
        {
                $validator = Validator::make($request->all(), [
                        'id' => 'required',
                ]);

                if ($validator->fails()) {
                        return [
                                'status' => false,
                                'message' => 'Validation Error',
                                'data' => $validator->errors(),
                        ];
                }

                try {
                        return $this->repository->deleteMenuMapping($request->all());
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => $e->getMessage(),
                                'data' => null,
                        ];
                }
        }
}
