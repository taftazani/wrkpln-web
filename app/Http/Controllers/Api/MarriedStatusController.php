<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MarriedStatus\MarriedStatusService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class MarriedStatusController extends Controller
{
    use ApiResponse;
    public function __construct(private MarriedStatusService $service)
    {
    }

    public function getMarriedStatus()
    {
        $result = $this->service->get();
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function makeMarriedStatus(Request $request)
    {
        $result = $this->service->make($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateMarriedStatus(Request $request)
    {
        $result = $this->service->update($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }


    public function deleteMarriedStatus(Request $request)
    {
        $result = $this->service->delete($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    // public function export()
    // {
    //     return Excel::download(new MarriedStatusExport, 'organizations.xlsx');
    // }
}
