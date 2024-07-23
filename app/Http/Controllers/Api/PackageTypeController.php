<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PackageType\PackageTypeService;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class PackageTypeController extends Controller
{
    use ApiResponse;

    public function __construct(private PackageTypeService $service)
    {
    }

    public function index(Request $request)
    {
        $result = $this->service->getAll($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function store(Request $request)
    {
        $result = $this->service->create($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function update(Request $request)
    {
        $result = $this->service->update($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function destroy(Request $request)
    {
        $result = $this->service->delete($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function bulkUpload(Request $request)
    {
        $result = $this->service->bulkUpload($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function export()
    {
        $result = $this->service->export();
        return $this->response($result['data'], $result['message'], $result['status']);
    }
}
