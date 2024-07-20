<?php

namespace App\Services\Gender;

use App\Repositories\Gender\GenderRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GenderService
{
        public function __construct(private GenderRepository $repository)
        {
        }

        public function get()
        {
                try {
                        return $this->repository->getGender();
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
                        return $this->repository->makeGender($request->all());
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
                        return $this->repository->updateGender($request->all());
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
                        'genders' => 'required|array',
                        'genders.*.id' => 'required|exists:genders,id',
                        'genders.*.name' => 'required|string|max:255',
                        'genders.*.detail' => 'required|string|max:255',
                        'genders.*.status' => 'required|integer|in:0,1',
                ]);


                if ($validator->fails()) {
                        return [
                                'status' => false,
                                'message' => 'Validation Error',
                                'data' => $validator->errors(),
                        ];
                }

                try {
                        return $this->repository->bulkUpdateGenders($request->all());
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
                        return $this->repository->deleteGender($request->all());
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => $e->getMessage(),
                                'data' => null,
                        ];
                }
        }
}
