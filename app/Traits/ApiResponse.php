<?php

namespace App\Traits;

trait ApiResponse
{
    protected function successResponseUser($data = [], $message = 'Operation successful', $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'user' => $data['user'],
            'token' => $data['token'],
            'permission' => $data['permission'],
        ], $code);
    }

    protected function successResponse($data = [], $message = 'Operation successful', $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function response($data = [], $message = 'Operation successful', $status = false, $code = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse($message = 'Operation failed', $code = 400, $data = [])
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}