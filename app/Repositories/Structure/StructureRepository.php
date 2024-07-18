<?php

namespace App\Repositories\Structure;

use App\Models\Structure;
use Exception;
use Illuminate\Support\Facades\DB;

class StructureRepository
{
    public function getStructure()
    {
        try {
            $structures = Structure::with(['children', 'organization', 'function', 'levelStructure', 'users'])->whereNull('parent_id')->orderBy('sort_order')->get();
            return [
                'status' => true,
                'message' => 'Success Getting Data Structure',
                'data' => $structures,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Getting Data Structure' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function makeStructure(array $data)
    {
        try {
            $structure = Structure::create($data);
            return [
                'status' => true,
                'message' => 'Success Creating Structure',
                'data' => $structure,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Creating Structure' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateStructure(array $data)
    {
        try {
            $structure = Structure::findOrFail($data['id']);
            $structure->update($data);

            return [
                'status' => true,
                'message' => 'Success Updating Structure',
                'data' => $structure,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Updating Structure' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function deleteStructure(array $data)
    {
        try {
            $structure = Structure::findOrFail($data['id']);
            $structure->delete();

            return [
                'status' => true,
                'message' => 'Success Deleting Structure',
                'data' => $structure,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Deleting Structure' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function reorderStructure(array $data)
    {
        DB::beginTransaction();
        try {
            foreach ($data['structures'] as $structure) {
                Structure::where('id', $structure['id'])->update(['sort_order' => $structure['sort_order']]);
            }
            DB::commit();

            return [
                'status' => true,
                'message' => 'Success Reordering Structures',
                'data' => true,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'status' => false,
                'message' => 'Error Reordering Structures' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function assignUserToStructure(array $data)
    {
        try {
            $structure = Structure::findOrFail($data['structure_id']);

            // Check if the user is already assigned
            if ($structure->users()->where('user_id', $data['user_id'])->exists()) {
                return [
                    'status' => false,
                    'message' => 'User is already assigned to this structure',
                    'data' => null,
                ];
            }

            // Attach the user to the structure
            $structure->users()->attach($data['user_id']);

            return [
                'status' => true,
                'message' => 'Success Assigning User to Structure',
                'data' => $structure,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Assigning User to Structure: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }


    public function removeUserFromStructure(array $data)
    {
        try {
            $structure = Structure::findOrFail($data['structure_id']);
            $structure->users()->detach($data['user_id']);

            return [
                'status' => true,
                'message' => 'Success Removing User from Structure',
                'data' => $structure,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Removing User from Structure' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
}