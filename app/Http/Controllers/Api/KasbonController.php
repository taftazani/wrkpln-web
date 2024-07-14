<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Kasbon\KasbonService;

class KasbonController extends Controller
{
    use ApiResponse;
    public function __construct(private KasbonService $service)
    {
    }

    public function getKasbon()
    {
        $result = $this->service->get();
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function getKasbonUser(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $result = $this->service->getUser($filter);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function makeKasbon(Request $request)
    {
        $result = $this->service->make($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateKasbon(Request $request)
    {
        $result = $this->service->update($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateKasbonReject(Request $request)
    {
        $result = $this->service->updateReject($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateKasbonApprove(Request $request)
    {
        $result = $this->service->updateApprove($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function deleteKasbon(Request $request)
    {
        $result = $this->service->delete($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
}
