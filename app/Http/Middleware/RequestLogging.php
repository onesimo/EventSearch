<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiLogging;

class RequestLogging
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $ApiLogging = new ApiLogging;
        $ApiLogging->user_id = auth()->user()->id;
        $ApiLogging->request = $request->getRequestUri();
        $ApiLogging->method = $request->method();
        $ApiLogging->ip = $request->ip();
        $ApiLogging->save();

        return $next($request);
    }
}
