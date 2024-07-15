<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Organizations\OrganizationsService;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{


    public function __construct(private OrganizationsService $organizationsService)
    {

    }
    public function showAll()
    {
        $service = $this->organizationsService->doGetAllData();
        return response()->json(['data'=> $service], 200);
    }

    public function bulkUpload(Request $request)
    {
        if($request->isJson()){

            $jsonData = $request->json()->all();
            $data = $jsonData['data'];
            $service = $this->organizationsService->doBulkUpload($data);

            return response()->json(['data'=> $service], 200);
        } else {
            return response()->json(['data'=> "Not found"], 404);
        }
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
