<?php


namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Organization\OrganizationService;
use App\Exports\OrganizationsExport;
use Maatwebsite\Excel\Facades\Excel;

class OrganizationController extends Controller
{
    use ApiResponse;
    public function __construct(private OrganizationService $service)
    {
    }

    public function getOrganization()
    {
        $result = $this->service->get();
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function makeOrganization(Request $request)
    {
        $result = $this->service->make($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateOrganization(Request $request)
    {
        $result = $this->service->update($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }


    public function deleteOrganization(Request $request)
    {
        $result = $this->service->delete($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function export()
    {
        return Excel::download(new OrganizationsExport, 'organizations.xlsx');
    }
}
