<?php

namespace App\Http\Controllers;

use App\Services\MasterPermission\MasterPermissionService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class MasterPermissionController extends Controller
{
    use ApiResponse;
    public function __construct(private MasterPermissionService $service)
    {
    }

    public function getMasterPermission()
    {
        $result = $this->service->get();
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function makeMasterPermission(Request $request)
    {
        $result = $this->service->make($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateMasterPermission(Request $request)
    {
        $result = $this->service->update($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }


    public function deleteMasterPermission(Request $request)
    {
        $result = $this->service->delete($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
}
