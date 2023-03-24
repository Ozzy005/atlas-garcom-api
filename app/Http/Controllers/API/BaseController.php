<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function sendResponse($data, $message = '', $code = 200): \Illuminate\Http\JsonResponse
    {
        $response = [
            'message' => $message,
            'data'    => $data
        ];

        return response()->json($response, $code);
    }

    public function sendError($error, $errorMessages = [], $code = 500): \Illuminate\Http\JsonResponse
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
