<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function sendResponse(mixed $data, string $message = '', int $code = 200): \Illuminate\Http\JsonResponse
    {
        $response = [
            'message' => $message,
            'data'    => $data
        ];

        return response()->json($response, $code);
    }

    public function sendError(string $error, mixed $errorMessages = [], int $code = 500): \Illuminate\Http\JsonResponse
    {
        $response = [
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['errors'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
