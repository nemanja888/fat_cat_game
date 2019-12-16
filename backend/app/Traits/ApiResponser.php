<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponser
{
    /**
     * @param $data
     * @param int $code
     * @return object
     */
    public function successResponse($data, $code = Response::HTTP_OK)
    {
        return response($data, $code)->header('Content-Type', 'application/json');
    }

    /**
     * @param $message
     * @param $code
     * @return object
     */
    public function errorResponse($message, $code): object
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    /**
     * @param $message
     * @param $code
     * @return object
     */
    public function errorMessage($message, $code): object
    {
        return response($message, $code)->header('Content-Type', 'application/json');
    }
}
