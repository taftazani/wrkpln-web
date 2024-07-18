<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EmployeeTypeService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class EmployeeTypeController extends Controller
{
    use ApiResponse;

    public function __construct(private EmployeeTypeService $service)
    {
    }

    public function index(Request $request)
    {
        $result = $this->service->getEmployeeTypes($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function store(Request $request)
    {
        $result = $this->service->createEmployeeType($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function update(Request $request, $id)
    {
        $result = $this->service->updateEmployeeType($request, $id);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function destroy($id)
    {
        $result = $this->service->deleteEmployeeType($id);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function bulkUpload(Request $request)
    {
        $result = $this->service->bulkUploadEmployeeTypes($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function export()
    {
        $result = $this->service->exportEmployeeTypes();
        return $this->response($result['data'], $result['message'], $result['status']);
    }
}