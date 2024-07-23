<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Gender\GenderService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class GenderController extends Controller
{
    use ApiResponse;
    public function __construct(private GenderService $service)
    {
    }

    public function getGender()
    {
        $result = $this->service->get();
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function makeGender(Request $request)
    {
        $result = $this->service->make($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateGender(Request $request)
    {
        $result = $this->service->update($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }


    public function deleteGender(Request $request)
    {
        $result = $this->service->delete($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    // public function export()
    // {
    //     return Excel::download(new GenderExport, 'organizations.xlsx');
    // }
}
