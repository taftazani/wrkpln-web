<?php

namespace App\Repositories\Holiday;

use App\Models\Holiday;
use Exception;
use Illuminate\Support\Facades\DB;

class HolidayRepository
{
    public function getAllHolidays($request)
    {
        try {
            $holidays = Holiday::orderBy('holiday_date')->paginate(10);
            return [
                'status' => true,
                'message' => 'Success Getting Data Holidays',
                'data' => $holidays,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Getting Data Holidays: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function createHoliday(array $data)
    {
        try {
            $holiday = Holiday::create($data);
            return [
                'status' => true,
                'message' => 'Success Creating Holiday',
                'data' => $holiday,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Creating Holiday: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateHoliday(array $data)
    {
        try {
            $holiday = Holiday::findOrFail($data['id']);
            $holiday->update($data);

            return [
                'status' => true,
                'message' => 'Success Updating Holiday',
                'data' => $holiday,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Updating Holiday: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function deleteHoliday(array $data)
    {
        try {
            $holiday = Holiday::findOrFail($data['id']);
            $holiday->delete();

            return [
                'status' => true,
                'message' => 'Success Deleting Holiday',
                'data' => $holiday,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Deleting Holiday: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
}