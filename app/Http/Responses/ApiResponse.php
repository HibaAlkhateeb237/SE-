<?php

namespace App\Http\Responses;

class ApiResponse
{
    public static function success(string $message = null, $data = null, int $status = 200)
    {
        return response()->json([
            'success'     => true,
            'status_code' => $status,
            'message'     => $message,
            'data'        => $data,
            'errors'      => []
        ], $status);
    }

    public static function error(string $message = null, $errors = [], int $status = 400)
    {
        return response()->json([
            'success'     => false,
            'status_code' => $status,
            'message'     => $message,
            'data'        => null,
            'errors'      => $errors
        ], $status);
    }

}
