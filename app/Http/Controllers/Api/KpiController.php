<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Kpi\KpiService;

class KpiController extends Controller
{
    use ApiResponse;
    public function __construct(private KpiService $service)
    {
    }
    public function getKpiAspects()
    {
        $result = $this->service->getAspects();
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function getKpiData(Request $request)
    {
        $userId = $request->user_id;
        $dateRange = $request->dateRange;

        $result = $this->service->getKpiData($userId, $dateRange);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function getKpi()
    {
        $result = $this->service->get();
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function getKpiUser(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $result = $this->service->getUser($filter);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function makeKpi(Request $request)
    {
        $result = $this->service->make($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateKpi(Request $request)
    {
        $result = $this->service->update($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateKpiReject(Request $request)
    {
        $result = $this->service->updateReject($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateKpiApprove(Request $request)
    {
        $result = $this->service->updateApprove($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function deleteKpi(Request $request)
    {
        $result = $this->service->delete($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
}
