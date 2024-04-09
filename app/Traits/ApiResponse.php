<?php

namespace App\Traits;

use App\Models\Log;
use App\Models\Exception;
use Illuminate\Support\Str;

trait ApiResponse
{
    // --- report error
    public function reportError($exception, $source, $request = null){


        //-- return
        return $this->handleError($exception->getMessage());
    }

    // --- handle respone
    public function handleResponse($result, $msg = null, $code = 200)
    {
        $res = [
            'success' => true,
            'data'    => $result,
            'message' => $msg,
        ];
        return response()->json($res, $code);
    }

    // --- handle error
    public function handleError($error, $errorMsg = [], $code = 200)
    {
        $res = [
            'success' => false,
            'message' => $error,
        ];
        if (!empty($errorMsg)) {
            $res['data'] = $errorMsg;
        }
        return response()->json($res, $code);
    }
}
