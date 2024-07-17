<?php

namespace App\Repositories\LevelStructure;

use App\Models\LevelStructure;
use App\Traits\ImageUpload;

use Exception;
use Illuminate\Support\Facades\DB;

class LevelStructureRepository
{
    use ImageUpload;

    public function getLevelStructure()
    {
        try {
            $level_structure = LevelStructure::orderByDesc('created_at')->get();
            return [
                'status' => true,
                'message' => 'Success Getting Data LevelStructure',
                'data' => $level_structure,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Getting Data LevelStructure' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function makeLevelStructure(array $data)
    {
        try {

            $level_structure = LevelStructure::create([
                'code' => $data['code'],
                'name' => $data['name'],
                'status' => 1,
            ]);

            return [
                'status' => true,
                'message' => 'Success Creating LevelStructure',
                'data' => $level_structure,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Creating LevelStructure' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateLevelStructure(array $data)
    {
        try {
            $level_structure = LevelStructure::findOrFail($data['id']);
            $level_structure->code = $data['code'];
            $level_structure->name = $data['name'];
            $level_structure->status = $data['status'];
            $level_structure->save();

            return [
                'status' => true,
                'message' => 'Success Changing Detail LevelStructure',
                'data' => $level_structure,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Changing Detail LevelStructure' . $e->getMessage(),
                'data' => null,
            ];
        }
    }



    public function deleteLevelStructure(array $data)
    {
        try {
            $level_structure = LevelStructure::findOrFail($data['id']);
            // $user = UserRole::where('level_structure_id', $level_structure->id)->first();
            // if ($user) {
            //     return [
            //         'status' => false,
            //         'message' => 'Error Deleting Detail LevelStructure - Masih Ada relasi User',
            //         'data' => $user,
            //     ];
            // }
            $level_structure->delete();

            return [
                'status' => true,
                'message' => 'Success Deleting Detail LevelStructure',
                'data' => $level_structure,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error Deleting Detail LevelStructure - ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function bulkDeleteLevelStructures(array $ids)
    {
        DB::beginTransaction();
        try {
            $deleted = LevelStructure::whereIn('id', $ids)->delete();
            DB::commit();

            return [
                'status' => true,
                'message' => 'Success Deleting LevelStructures',
                'data' => $deleted,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'status' => false,
                'message' => 'Error Deleting LevelStructures - ' . $e->getMessage(),
                'data' => 0,
            ];
        }
    }

    public function bulkUpdateLevelStructures(array $level_structures)
    {
        DB::beginTransaction();
        try {
            foreach ($level_structures as $data) {
                $level_structure = LevelStructure::findOrFail($data['id']);
                if (isset($data['code'])) $level_structure->code = $data['code'];
                if (isset($data['name'])) $level_structure->name = $data['name'];
                if (isset($data['status'])) $level_structure->status = $data['status'];
                $level_structure->save();
            }
            DB::commit();

            return [
                'status' => true,
                'message' => 'Success Updating LevelStructures',
                'data' => true,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'status' => false,
                'message' => 'Error Updating LevelStructures - ' . $e->getMessage(),
                'data' => false,

            ];
        }
    }
}
