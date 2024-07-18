<?php

namespace App\Repositories;

use App\Models\PackageType;
use Exception;
use Illuminate\Http\Request;

class PackageTypeRepository
{
    public function getAll(Request $request)
    {
        try {
            $query = PackageType::query();
            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }
            return $query->orderBy('code')->paginate(10);
        } catch (Exception $e) {
            throw new Exception('Error Getting Package Types: ' . $e->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            return PackageType::create($data);
        } catch (Exception $e) {
            throw new Exception('Error Creating Package Type: ' . $e->getMessage());
        }
    }

    public function update(array $data, $id)
    {
        try {
            $packageType = PackageType::findOrFail($id);
            $packageType->update($data);
            return $packageType;
        } catch (Exception $e) {
            throw new Exception('Error Updating Package Type: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $packageType = PackageType::findOrFail($id);
            $packageType->delete();
        } catch (Exception $e) {
            throw new Exception('Error Deleting Package Type: ' . $e->getMessage());
        }
    }
}