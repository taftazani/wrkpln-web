<?php

namespace App\Services\MasterSchedule;

use Exception;
use App\Repositories\MasterSchedule\MasterScheduleRepository;
use App\Traits\JwtHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterScheduleService
{
    public function __construct(private MasterScheduleRepository $repository)
    {
    }
    public function getUser()
    {
        try {
            return $this->repository->getScheduleUser();
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function getFilterUser($filter)
    {
        try {
            return $this->repository->getScheduleFilterUser($filter);
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage() . 'services',
                'data' => null,
            ];
        }
    }
    public function get()
    {
        try {
            return $this->repository->getSchedule();
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
            'id_shift' => 'required',
            'id_user' => 'required',
            'id_place' => 'required',
            'tanggal_dari' => 'required',
            'tanggal_sampai' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            return $this->repository->makeSchedule($request->all());
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
            return $this->repository->deleteSchedule($request->all());
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function deleteBulk(Request $request)
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
            return $this->repository->deleteScheduleBulk($request->all());
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
