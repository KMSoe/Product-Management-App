<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @param $result
     * @param $message
     *
     * @return \Illuminate\Http\Response
     */

    public function sendCreatedResponse($id, $data)
    {
        $response = [
            'success' => true,
            "id" => $id,
            'data' => $data,
        ];

        return response()->json($response, 201);
    }

    public function sendUpdatedResponse($id, $data)
    {
        $response = [
            'success' => true,
            "id" => $id,
            'data' => $data,
        ];

        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @param $error
     * @param  array  $errorMessages
     * @param  int  $code
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($errors, $status)
    {
        $response = [
            'success' => false,
            'errors' => $errors,
        ];

        return response()->json($response, $status);
    }


    /**
     * return Unauthorized response.
     *
     * @param $error
     * @param  int  $code
     *
     * @return \Illuminate\Http\Response
     */
    public function unauthorizedResponse($error = 'Forbidden', $code = 403)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        return response()->json($response, $code);
    }
}
