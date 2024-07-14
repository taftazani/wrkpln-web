<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Izin\IzinService;

class IzinController extends Controller
{
    use ApiResponse;
    public function __construct(private IzinService $service)
    {
    }

    public function getIzin()
    {
        $result = $this->service->get();
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function getIzinUser(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $result = $this->service->getUser($filter);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function makeIzin(Request $request)
    {
        $result = $this->service->make($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateIzin(Request $request)
    {
        $result = $this->service->update($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateIzinReject(Request $request)
    {
        $result = $this->service->updateReject($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateIzinApprove(Request $request)
    {
        $result = $this->service->updateApprove($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function deleteIzin(Request $request)
    {
        $result = $this->service->delete($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
}
