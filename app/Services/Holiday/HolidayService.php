<?php

namespace App\Services\Holiday;

use App\Repositories\Holiday\HolidayRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HolidayService
{
    public function __construct(private HolidayRepository $repository)
    {
    }

    public function getAll(Request $request)
    {
        try {
            return $this->repository->getAllHolidays($request);
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'holiday_date' => 'required|date|unique:holidays,holiday_date',
            'holiday_name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            return $this->repository->createHoliday($request->all());
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:holidays,id',
            'holiday_date' => 'required|date|unique:holidays,holiday_date,' . $request->id,
            'holiday_name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            return $this->repository->updateHoliday($request->all());
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:holidays,id',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            return $this->repository->deleteHoliday($request->all());
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function bulkUpload(Request $request)
    {
        // Implement the bulk upload logic here
    }
}
