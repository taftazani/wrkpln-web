<?php

namespace App\Repositories\EmployeeType;

use App\Models\EmployeeType;

class EmployeeTypeRepository
{
    public function getEmployeeTypes($request)
    {
        return EmployeeType::query()
            ->when($request->search, function ($query, $search) {
                return $query->where('type', 'like', "%{$search}%");
            })
            ->orderBy('type')
            ->get();
    }

    public function getLastEmployeeType()
    {
        return EmployeeType::latest('id')->first();
    }

    public function createEmployeeType(array $data)
    {
        return EmployeeType::create($data);
    }

    public function updateEmployeeType($id, array $data)
    {
        $employeeType = EmployeeType::findOrFail($id);
        $employeeType->update($data);

        return $employeeType;
    }

    public function deleteEmployeeType($id)
    {
        EmployeeType::findOrFail($id)->delete();
    }
}
