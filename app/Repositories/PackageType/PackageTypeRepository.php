<?php

namespace App\Repositories\PackageType;

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
            return $query->orderBy('code')->get();
        } catch (Exception $e) {
            throw new Exception('Error Getting Package Types: ' . $e->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            // Retrieve the last function
            $lastPackageType = PackageType::latest('id')->first();

            // Calculate the new ID (increment by 1)
            $newId = $lastPackageType ? $lastPackageType->id + 1 : 1;

            // Determine the length of the new ID and dynamically pad with zeros if necessary
            $length = max(3, strlen((string) $newId));
            $newCode = 'PAKET' . str_pad($newId, $length, '0', STR_PAD_LEFT);
            $input = [
                'code' => $newCode,
                'name' => $data['name'],
                'price' => $data['price'],
            ];
            return PackageType::create($input);
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
