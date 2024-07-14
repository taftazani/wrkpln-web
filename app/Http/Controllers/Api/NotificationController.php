<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MasterNotification\MasterNotificationService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponse;
    public function __construct(private MasterNotificationService $service)
    {
    }

    public function getMasterNotification()
    {
        $result = $this->service->get();
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function getMasterNotificationUser()
    {
        $result = $this->service->getUser();
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function makeMasterNotification(Request $request)
    {
        $result = $this->service->make($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateMasterNotification(Request $request)
    {
        $result = $this->service->update($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }


    public function deleteMasterNotification(Request $request)
    {
        $result = $this->service->delete($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function temporaryDeleteMasterNotification(Request $request)
    {
        $result = $this->service->deleteTemporary($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
}
