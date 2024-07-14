<?php

namespace App\Services\MasterUser;

use Exception;
use App\Repositories\MasterUser\MasterUserRepository;
use App\Traits\JwtHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterUserService
{
    public function __construct(private MasterUserRepository $repository)
    {
    }

    public function get()
    {
        try {
            return $this->repository->getUser();
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function getRole()
    {
        try {
            return $this->repository->getRole();
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
            'email' => 'required',
            'profile_image' => 'required',
            'tgl_lahir' => 'required',
            'phone' => 'required',
            'norek' => 'required',
            'bank_name' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            if ($request->password) {
                return $this->repository->makeUser($request->all());
            } else {
                return $this->repository->makeUserAdmin($request->all());
            }
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function makeArea(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'area_id' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            return $this->repository->makeUserArea($request->all());
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateArea(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'area_id' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            return $this->repository->updateUserArea($request->all());
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function removeArea(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'area_id' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            return $this->repository->removeUserArea($request->all());
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
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            if ($request->password) {
                return $this->repository->updateUserPassword($request->all());
            } else {
                return $this->repository->updateUser($request->all());
            }
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function updateAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            return $this->repository->adminUpdateUser($request->all());
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
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            return $this->repository->deleteUser($request->all());
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
