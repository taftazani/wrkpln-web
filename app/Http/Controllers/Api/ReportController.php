<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Report\ReportService;

class ReportController extends Controller
{
    use ApiResponse;
    public function __construct(private ReportService $service)
    {
    }
    public function getKpiAspects()
    {
        $result = $this->service->get();
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function getFilteredReport(Request $request)
    {
        $result = $this->service->getFilteredReport(
            $request->user_id,
            $request->year,
            $request->month,
            $request->day,
            $request->start_date,
            $request->end_date
        );
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    // public function exportToExcel(Request $request)
    // {
    //     return $this->service->exportToExcel($request->user_id, $request->year);
    // }

    // public function exportToPdf(Request $request)
    // {
    //     return $this->service->exportToPdf($request->user_id, $request->year);
    // }
}
