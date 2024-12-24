<?php

namespace App\Traits;

trait ApiResponseTrait
{
    public function successResponse($data = null, $message = "Success", $status = 200)
    {
        return response([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public function errorResponse($message = 'Error', $status = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}