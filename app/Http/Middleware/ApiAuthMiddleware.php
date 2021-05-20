<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\JwtAuth;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $jwtAuth = new JwtAuth();
        $token = $request->header('token');
        $logged = $jwtAuth->verify($token);
        if($logged){
            return $next($request);
        }else{
            $respose = array(
                'status' => 'error',
                'code' => 401,
                'message' => 'No posee privilegios para utilizar este recurso'
            );
            return response()->json($respose,$respose['code']);
        }
    }
}
