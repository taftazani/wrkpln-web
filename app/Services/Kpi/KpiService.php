<?php

namespace App\Services\Kpi;

use App\Repositories\Kpi\KpiRepository;
use Exception;
use App\Traits\JwtHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KpiService
{
    public function __construct(private KpiRepository $repository)
    {
    }

    public function getAspects()
    {
        try {
            return $this->repository->getKpiAspects();
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage() . ' in service',
                'data' => null,
            ];
        }
    }

    public function getKpiData($userId, $dateRange)
    {
        try {
            return $this->repository->getKpiData($userId, $dateRange);
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage() . ' in service',
                'data' => null,
            ];
        }
    }
    public function get()
    {
        try {
            return $this->repository->getKpi();
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function getUser($filter)
    {
        try {
            return $this->repository->getKpiUser($filter);
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
            'user_id' => 'required',
            'aspect_id' => 'required',
            'rate' => 'required',
            'period' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            return $this->repository->makeKpi($request->all());
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
            'latitude' => 'required',
            'longitude' => 'required',
            'radius' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            return $this->repository->updateKpi($request->all());
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateReject(Request $request)
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
            return $this->repository->updateKpiReject($request->all());
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateApprove(Request $request)
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
            return $this->repository->updateKpiApprove($request->all());
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
            return $this->repository->deleteKpi($request->all());
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
