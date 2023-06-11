<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $message = "";

    protected $error = false;
    protected $statusCode = 200;

    public function successResponse(String $message, $data= [])
    {
        $this->message = $message;
        return $this->response($data);
    }

    public function errorResponse(String $message, $statusCode = 404, $data= [])
    {
        $this->message = $message;
        $this->error = true;
        $this->statusCode = $statusCode;
        return $this->response($data);
    }

    public function response($data = [], $headers = [])
    {
        $data = [
            'message' => $this->message,
            'error' => $this->error,
            'status_code' => $this->statusCode,
            'response' => empty($data) ? null : $data,
        ];

        return response()->json($data, $this->statusCode, $headers);
    }
}
