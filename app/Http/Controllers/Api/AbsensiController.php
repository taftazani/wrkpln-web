<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Absensi\AbsensiService;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;


class AbsensiController extends Controller
{
    use ApiResponse;

    public function __construct(private AbsensiService $service)
    {
    }

    public function getAbsenAll()
    {
        $result = $this->service->getAll();
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function getAbsen()
    {
        $result = $this->service->get();
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function getAbsenUser(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $result = $this->service->getUser($filter);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function makeAbsen(Request $request)
    {
        $result = $this->service->make($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function makeAbsenAttachment(Request $request)
    {
        $result = $this->service->makeAttachment($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function removeAbsenAttachment(Request $request)
    {
        $result = $this->service->removeAttachment($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function makeAbsenKeluar(Request $request)
    {
        $result = $this->service->make($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
}
