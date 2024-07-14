<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MasterSchedule\MasterScheduleService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class MasterScheduleController extends Controller
{
    use ApiResponse;
    public function __construct(private MasterScheduleService $service)
    {
    }
    public function getScheduleUser()
    {
        $result = $this->service->getUser();
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function getScheduleFilterUser(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $result = $this->service->getFilterUser($filter);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function getSchedule()
    {
        $result = $this->service->get();
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function makeSchedule(Request $request)
    {
        $result = $this->service->make($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }


    public function deleteSchedule(Request $request)
    {
        $result = $this->service->delete($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function deleteScheduleBulk(Request $request)
    {
        $result = $this->service->deleteBulk($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
}
