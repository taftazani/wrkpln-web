<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Holiday\HolidayService;
use App\Exports\HolidaysExport;
use Maatwebsite\Excel\Facades\Excel;

class HolidayController extends Controller
{
    use ApiResponse;

    public function __construct(private HolidayService $service)
    {
    }

    public function getHolidays(Request $request)
    {
        $result = $this->service->getAll($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function createHoliday(Request $request)
    {
        $result = $this->service->create($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateHoliday(Request $request)
    {
        $result = $this->service->update($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function deleteHoliday(Request $request)
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
        return Excel::download(new HolidaysExport, 'holidays.xlsx');
    }
}