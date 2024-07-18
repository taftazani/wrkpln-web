<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Structure\StructureService;
use App\Exports\StructuresExport;
use Maatwebsite\Excel\Facades\Excel;

class StructureController extends Controller
{
    use ApiResponse;

    public function __construct(private StructureService $service)
    {
    }

    public function getStructure()
    {
        $result = $this->service->get();
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function makeStructure(Request $request)
    {
        $result = $this->service->make($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateStructure(Request $request)
    {
        $result = $this->service->update($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function deleteStructure(Request $request)
    {
        $result = $this->service->delete($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function reorderStructure(Request $request)
    {
        $result = $this->service->reorder($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function assignUserToStructure(Request $request)
    {
        $result = $this->service->assignUser($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function removeUserFromStructure(Request $request)
    {
        $result = $this->service->removeUser($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    // public function export()
    // {
    //     return Excel::download(new StructuresExport, 'structures.xlsx');
    // }
}
