<?php

namespace App\Services\Religion;

use App\Repositories\Religion\ReligionRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReligionService
{
        public function __construct(private ReligionRepository $repository)
        {
        }

        public function get()
        {
                try {
                        return $this->repository->getReligion();
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
                        'name' => 'required',
                ]);

                if ($validator->fails()) {
                        return [
                                'status' => false,
                                'message' => 'Validation Error',
                                'data' => $validator->errors(),
                        ];
                }

                try {
                        return $this->repository->makeReligion($request->all());
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
                        'name' => 'required',
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
                        return $this->repository->updateReligion($request->all());
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
                        'religions' => 'required|array',
                        'religions.*.id' => 'required|exists:religions,id',
                        'religions.*.name' => 'required|string|max:255',
                        'religions.*.status' => 'required|integer|in:0,1',
                ]);


                if ($validator->fails()) {
                        return [
                                'status' => false,
                                'message' => 'Validation Error',
                                'data' => $validator->errors(),
                        ];
                }

                try {
                        return $this->repository->bulkUpdateReligions($request->all());
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
                        return $this->repository->deleteReligion($request->all());
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => $e->getMessage(),
                                'data' => null,
                        ];
                }
        }
}
