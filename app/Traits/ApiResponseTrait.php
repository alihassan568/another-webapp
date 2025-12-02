<?php

namespace App\Traits;

trait ApiResponseTrait
{
    public function success($data = [], $message = 'Success', $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'result' => $data
        ], $code);
    }

    public function error($message = 'Error', $code = 400, $data = [])
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $data
        ], $code);
    }
}
