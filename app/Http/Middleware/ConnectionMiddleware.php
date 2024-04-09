<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConnectionMiddleware
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $country = request()->header('x-country');

        if($country == 'US'){
            config(['database.default' => 'pgsql_us']);
        }
        else if($country == 'RU'){
            config(['database.default' => 'pgsql_ru']);
        }
        else {
            return $this->handleError("Server does not support your country");
        }
        return $next($request);
    }
}
