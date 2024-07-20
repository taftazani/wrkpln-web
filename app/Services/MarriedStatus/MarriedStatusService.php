<?php

namespace App\Services\MarriedStatus;

use App\Repositories\MarriedStatus\MarriedStatusRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarriedStatusService
{
        public function __construct(private MarriedStatusRepository $repository)
        {
        }

        public function get()
        {
                try {
                        return $this->repository->getMarriedStatus();
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
                        return $this->repository->makeMarriedStatus($request->all());
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
                        return $this->repository->updateMarriedStatus($request->all());
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
                        'married_statuses' => 'required|array',
                        'married_statuses.*.id' => 'required|exists:married_statuses,id',
                        'married_statuses.*.name' => 'required|string|max:255',
                        'married_statuses.*.status' => 'required|integer|in:0,1',
                ]);


                if ($validator->fails()) {
                        return [
                                'status' => false,
                                'message' => 'Validation Error',
                                'data' => $validator->errors(),
                        ];
                }

                try {
                        return $this->repository->bulkUpdateMarriedStatuss($request->all());
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
                        return $this->repository->deleteMarriedStatus($request->all());
                } catch (Exception $e) {
                        return [
                                'status' => false,
                                'message' => $e->getMessage(),
                                'data' => null,
                        ];
                }
        }
}
