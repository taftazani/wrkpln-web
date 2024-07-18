<?php

namespace App\Services;

use App\Repositories\EmployeeTypeRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeTypeService
{
    public function __construct(private EmployeeTypeRepository $repository)
    {
    }

    public function getEmployeeTypes(Request $request)
    {
        try {
            $employeeTypes = $this->repository->getEmployeeTypes($request);
            return [
                'status' => true,
                'message' => 'Employee Types retrieved successfully',
                'data' => $employeeTypes
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error retrieving Employee Types: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function createEmployeeType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|unique:employee_types,type',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            $lastEmployeeType = $this->repository->getLastEmployeeType();
            $newCode = str_pad($lastEmployeeType ? $lastEmployeeType->id + 1 : 1, 2, '0', STR_PAD_LEFT);

            $employeeType = $this->repository->createEmployeeType([
                'code' => $newCode,
                'type' => $request->type,
                'status' => $request->status
            ]);

            return [
                'status' => true,
                'message' => 'Employee Type created successfully',
                'data' => $employeeType
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error creating Employee Type: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function updateEmployeeType(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|unique:employee_types,type,' . $id,
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            $employeeType = $this->repository->updateEmployeeType($id, $request->only('type', 'status'));

            return [
                'status' => true,
                'message' => 'Employee Type updated successfully',
                'data' => $employeeType
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error updating Employee Type: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function deleteEmployeeType($id)
    {
        try {
            $this->repository->deleteEmployeeType($id);

            return [
                'status' => true,
                'message' => 'Employee Type deleted successfully',
                'data' => null
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error deleting Employee Type: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function bulkUploadEmployeeTypes(Request $request)
    {
        // Handle bulk upload logic here
        // You can use packages like Maatwebsite Excel for handling Excel files

        return [
            'status' => false,
            'message' => 'Bulk upload not implemented yet',
            'data' => null
        ];
    }

    public function exportEmployeeTypes()
    {
        // Handle export logic here
        // You can use packages like Maatwebsite Excel for exporting data to Excel

        return [
            'status' => false,
            'message' => 'Export not implemented yet',
            'data' => null
        ];
    }
}