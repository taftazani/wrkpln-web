<?php

namespace App\Services\PackageType;

use App\Repositories\PackageType\PackageTypeRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackageTypeService
{
    public function __construct(private PackageTypeRepository $repository)
    {
    }

    public function getAll(Request $request)
    {
        try {
            return [
                'status' => true,
                'message' => 'Success Getting Package Types',
                'data' => $this->repository->getAll($request),
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Getting Package Types: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            $packageType = $this->repository->create($request->all());
            return [
                'status' => true,
                'message' => 'Success Creating Package Type',
                'data' => $packageType,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Creating Package Type: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'status' => 'required|in:0,1'
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            $packageType = $this->repository->update($request->all(), $request->id);
            return [
                'status' => true,
                'message' => 'Success Updating Package Type',
                'data' => $packageType,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Updating Package Type: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function delete(Request $request)
    {
        try {
            $this->repository->delete($request->id);
            return [
                'status' => true,
                'message' => 'Success Deleting Package Type',
                'data' => null,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Deleting Package Type: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function bulkUpload(Request $request)
    {
        // Handle bulk upload logic here
    }

    public function export()
    {
        // Handle export logic here
    }
}
