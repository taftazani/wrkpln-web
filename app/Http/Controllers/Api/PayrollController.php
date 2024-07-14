<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Payroll\PayrollService;

class PayrollController extends Controller
{
    use ApiResponse;
    public function __construct(private PayrollService $service)
    {
    }

    public function getPayroll(Request $request)
    {
        $userId = $request->id_user;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $result = $this->service->get($userId, $start_date, $end_date);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function getPayrollUser(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $result = $this->service->getUser($filter);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function makePayroll(Request $request)
    {
        $result = $this->service->make($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updatePayroll(Request $request)
    {
        $result = $this->service->update($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function updatePayrollBulk(Request $request)
    {
        $result = $this->service->updateBulk($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function updatePayrollOne(Request $request)
    {
        $result = $this->service->updateOne($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function updatePayrollReject(Request $request)
    {
        $result = $this->service->updateReject($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updatePayrollApprove(Request $request)
    {
        $result = $this->service->updateApprove($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function deletePayroll(Request $request)
    {
        $result = $this->service->delete($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
}
