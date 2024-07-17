<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LevelStructureExport;
use App\Services\LevelStructure\LevelStructureService;

class LevelStructureController extends Controller
{
    use ApiResponse;
    public function __construct(private LevelStructureService $service)
    {
    }

    public function getLevelStructure()
    {
        $result = $this->service->get();
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function makeLevelStructure(Request $request)
    {
        $result = $this->service->make($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateLevelStructure(Request $request)
    {
        $result = $this->service->update($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }


    public function deleteLevelStructure(Request $request)
    {
        $result = $this->service->delete($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function export()
    {
        return Excel::download(new LevelStructureExport, 'organizations.xlsx');
    }
}
