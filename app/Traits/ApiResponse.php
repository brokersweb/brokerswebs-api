<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponse
{

    public function successResponse($data, $statusCode = Response::HTTP_OK)
    {
        return response()->json(['data' => $data], $statusCode);
    }

    public function successResponseWithout($data, $statusCode = Response::HTTP_OK)
    {
        return response()->json($data, $statusCode);
    }
    public function errorResponse($errorMessage, $statusCode)
    {
        return response()->json(['error' => $errorMessage, 'error_code' => $statusCode], $statusCode);
    }
    public function successResponseWithMessage($message, $statusCode = Response::HTTP_OK)
    {
        return response()->json(['message' => $message], $statusCode);
    }
    public function errorResponseBadRequest($errorMessage)
    {
        return response()->json(['error' => $errorMessage, 'error_code' => Response::HTTP_BAD_REQUEST], Response::HTTP_BAD_REQUEST);
    }
}
