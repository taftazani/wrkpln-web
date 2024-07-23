<?php

namespace App\Services\Structure;

use App\Repositories\Structure\StructureRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StructureService
{
    public function __construct(private StructureRepository $repository)
    {
    }

    public function get()
    {
        try {
            return $this->repository->getStructure();
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function make(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'organization_id.value' => 'required|exists:organizations,id',
            'function_id.value' => 'required|exists:functions,id',
            'level_structure_id.value' => 'required|exists:level_structures,id',
            'plan_man_power' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            return $this->repository->makeStructure($request->all());
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:structures,id',
            'name' => 'required',
            'organization_id' => 'required|exists:organizations,id',
            'function_id' => 'required|exists:functions,id',
            'level_structure_id' => 'required|exists:level_structures,id',
            'plan_man_power' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            return $this->repository->updateStructure($request->all());
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:structures,id',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            return $this->repository->deleteStructure($request->all());
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'structures' => 'required|array',
            'structures.*.id' => 'required|exists:structures,id',
            'structures.*.sort_order' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            return $this->repository->reorderStructure($request->all());
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function assignUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'structure_id' => 'required|exists:structures,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            return $this->repository->assignUserToStructure($request->all());
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function removeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'structure_id' => 'required|exists:structures,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            return $this->repository->removeUserFromStructure($request->all());
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }
}