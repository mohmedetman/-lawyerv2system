<?php

namespace App\Helpers;

use App\Enums\JsonResponseStatus;

trait JsonResponse
{
    public static function success($data = [], $message = 'Success', $statusCode = 200)
    {
        $response = [
            'status' => JsonResponseStatus::SUCCESS->value,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $statusCode);
    }

    public static function error($message = 'Error', $statusCode = 500)
    {
        $response = [
            'status' => JsonResponseStatus::ERROR->value,
            'message' => $message,
        ];

        return response()->json($response, $statusCode);
    }
}
