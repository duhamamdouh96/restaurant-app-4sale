<?php

namespace App\Common;

class Response
{
    public function success($data = null, $message = 'Success')
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], 200);
    }

    public function validationError($message = 'Error', $data = null)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $data->errors(),
        ], 422);
    }

    public function exception($message = 'Exception', $statusCode = 401, $errorCode = null)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'code' => $errorCode
        ], $statusCode);
    }

    public function error($message = 'Error', $data = null)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data,
        ], 400);
    }
}
