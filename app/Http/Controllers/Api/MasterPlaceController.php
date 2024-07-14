<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MasterPlace\MasterPlaceService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class MasterPlaceController extends Controller
{
    use ApiResponse;
    public function __construct(private MasterPlaceService $service)
    {
    }

    public function getPlace()
    {
        $result = $this->service->get();
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function makePlace(Request $request)
    {
        $result = $this->service->make($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updatePlace(Request $request)
    {
        $result = $this->service->update($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function deletePlace(Request $request)
    {
        $result = $this->service->delete($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
}
