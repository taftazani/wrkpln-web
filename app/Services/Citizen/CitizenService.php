<?php

namespace App\Services\Citizen;

use App\Repositories\Citizen\CitizenRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CitizenService
{
        public function __construct(private CitizenRepository $repository)
        {
        }

        public function get()
        {
                try {
                        return $this->repository->getCitizen();
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
                        return $this->repository->makeCitizen($request->all());
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
                        return $this->repository->updateCitizen($request->all());
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
                        return $this->repository->bulkUpdateCitizens($request->all());
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
                        return $this->repository->deleteCitizen($request->all());
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => $e->getMessage(),
                                'data' => null,
                        ];
                }
        }
}
