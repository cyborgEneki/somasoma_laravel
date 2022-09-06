<?php

namespace App\Traits;

trait ResponseApi
{
    public function coreResponse($message, $data = null, $statusCode, $isSuccess = true)
    {
        if (!$message) {
            return response()->json(['message' => 'Message is required'], 500);
        }

        if ($isSuccess) {
            return response()->json([
                'message' => $message,
                'results' => $data,
                'code' => $statusCode,
                'success' => true
            ], $statusCode);
        } else {
            return response()->json([
                'message' => $message,
                'success' => false,
                'code' => $statusCode,
            ], $statusCode);
        }
    }

    public function success($message, $data, $statusCode = 200)
    {
        return $this->coreResponse($message, $data, $statusCode);
    }

    public function error($message, $statusCode = 500)
    {
        return $this->coreResponse($message, null, $statusCode, false);
    }
}
