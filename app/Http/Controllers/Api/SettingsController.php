<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LoginTimeout;
use App\Models\Workday;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    use ApiResponse;

    public function updateLoginTimeout(Request $request)
    {
        $request->validate([
            'timeout_hours' => 'required|integer|min:1|max:24',
        ]);

        try {
            $loginTimeout = LoginTimeout::first();
            if ($loginTimeout) {
                $loginTimeout->update(['timeout_hours' => $request->input('timeout_hours')]);
            } else {
                $loginTimeout = LoginTimeout::create(['timeout_hours' => $request->input('timeout_hours')]);
            }

            return $this->response($loginTimeout, 'Login timeout updated successfully', true);
        } catch (\Exception $e) {
            return $this->errorResponse('Error updating login timeout: ' . $e->getMessage());
        }
    }

    public function updateWorkday(Request $request, $id)
    {
        $request->validate([
            'is_workday' => 'required|boolean',
        ]);

        try {
            $workday = Workday::findOrFail($id);
            $workday->update(['is_workday' => $request->input('is_workday')]);

            return $this->response($workday, 'Workday updated successfully', true);
        } catch (\Exception $e) {
            return $this->errorResponse('Error updating workday: ' . $e->getMessage());
        }
    }
}