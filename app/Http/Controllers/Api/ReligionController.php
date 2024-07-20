<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Religion\ReligionService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ReligionController extends Controller
{
    use ApiResponse;
    public function __construct(private ReligionService $service)
    {
    }

    public function getReligion()
    {
        $result = $this->service->get();
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function makeReligion(Request $request)
    {
        $result = $this->service->make($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateReligion(Request $request)
    {
        $result = $this->service->update($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function deleteReligion(Request $request)
    {
        $result = $this->service->delete($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
}
