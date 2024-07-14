<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MasterUser\MasterUserService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class MasterUserController extends Controller
{
    use ApiResponse;
    public function __construct(private MasterUserService $service)
    {
    }

    public function getUser()
    {
        $result = $this->service->get();
        return $this->response($result['data'], $result['message'], $result['status']);
    }


    public function getRole()
    {
        $result = $this->service->getRole();
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function makeUser(Request $request)
    {
        $result = $this->service->make($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function makeUserArea(Request $request)
    {
        $result = $this->service->makeArea($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateUserArea(Request $request)
    {
        $result = $this->service->updateArea($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function removeUserArea(Request $request)
    {
        $result = $this->service->removeArea($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateUser(Request $request)
    {
        $result = $this->service->update($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function adminUpdateUser(Request $request)
    {
        $result = $this->service->updateAdmin($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function deleteUser(Request $request)
    {
        $result = $this->service->delete($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
}
