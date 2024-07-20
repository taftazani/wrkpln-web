<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MenuMapping\MenuMappingService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class MenuMappingController extends Controller
{
    use ApiResponse;
    public function __construct(private MenuMappingService $service)
    {
    }

    public function getMenuMapping()
    {
        $result = $this->service->get();
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function makeMenuMapping(Request $request)
    {
        $result = $this->service->make($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateMenuMapping(Request $request)
    {
        $result = $this->service->update($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function deleteMenuMapping(Request $request)
    {
        $result = $this->service->delete($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
}
