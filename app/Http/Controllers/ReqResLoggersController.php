<?php

namespace App\Http\Controllers;

use App\Models\RequestLoggers;
use App\Models\ResponseLoggers;
use Illuminate\Http\Request;

class ReqResLoggersController extends Controller
{
    public function __construct(
        private RequestLoggers $requestLoggers,
        private ResponseLoggers $responseLoggers
    ){}

    public function response (){
        return $this->responseLoggers->all();
    }

    public function request (){
        return $this->requestLoggers->all();
    }
}
