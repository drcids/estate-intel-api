<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseApiController extends Controller
{
    public function returnResponse($status, $code = 200, $data = [], $message = null ) {

        $response = [
            'status_code' => $code, 
            'status' => $status, 
            'data' => $data
        ];

        if(isset($message)){

            $response['message'] = $message;
        }

        return response()->json($response, $code);

    }
}
