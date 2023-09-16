<?php

namespace App\Http\Middleware;

use App\Models\RequestLoggers;
use App\Models\ResponseLoggers;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestResponseLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if ($request->path() == 'api/request' || $request->path() == 'api/response') {
            return $next($request);
        }else {
            RequestLoggers::create([
                'method' => $request->method(),
                'url' => $request->url(),
                'ip' => $request->ip(),
                'request' => $request->getContent(),
            ]);
            $response = $next($request);
            ResponseLoggers::create([
                'method' => $request->method(),
                'age' => $response->getAge(),
                'statusCode' => $response->getStatusCode(),
                'response' =>$response->getContent(),
            ]);
            return $response;
        }
    }
}
