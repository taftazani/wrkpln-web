<?php

namespace App\Services\Report;

use Exception;
use App\Repositories\Report\ReportRepository;
use App\Traits\JwtHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportService
{
    public function __construct(private ReportRepository $repository)
    {
    }

    public function get()
    {
        try {
            return $this->repository->getReport();
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function getFilteredReport($userId, $year, $month, $day, $startDate, $endDate)
    {
        try {
            return $this->repository->getFilteredReport($userId, $year, $month, $day, $startDate, $endDate);
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    // public function exportToExcel($userId, $year)
    // {
    //     // Implement the logic to export to Excel
    // }

    // public function exportToPdf($userId, $year)
    // {
    //     // Implement the logic to export to PDF
    // }
}
