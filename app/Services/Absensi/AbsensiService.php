<?php

namespace App\Services\Absensi;

use Exception;
use App\Repositories\Absensi\AbsensiRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AbsensiService
{
    public function __construct(private AbsensiRepository $repository)
    {
    }
    public function getAll()
    {
        try {
            return $this->repository->getAbsenAll();
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function get()
    {
        try {
            return $this->repository->getAbsen();
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }
    public function getUser($filter)
    {
        try {
            return $this->repository->getAbsenUser($filter);
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
            'lat1' => 'required',
            'lon1' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            if ($request->clock_out) {
                return $this->repository->makeAbsenKeluar($request->all());
            } else {
                return $this->repository->makeAbsenMasuk($request->all());
            }
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function makeAttachment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pict' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            return $this->repository->makeAbsenAttachment($request->all());
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function removeAttachment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attachment_id' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ];
        }

        try {
            return $this->repository->removeAbsenAttachment($request->all());
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
