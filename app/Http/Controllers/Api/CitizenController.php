<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Citizen\CitizenService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CitizenController extends Controller
{
    use ApiResponse;
    public function __construct(private CitizenService $service)
    {
    }

    public function getCitizen()
    {
        $result = $this->service->get();
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function makeCitizen(Request $request)
    {
        $result = $this->service->make($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateCitizen(Request $request)
    {
        $result = $this->service->update($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function deleteCitizen(Request $request)
    {
        $result = $this->service->delete($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
}
