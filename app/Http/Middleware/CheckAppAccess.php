<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAppAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $app, string $level): Response
    {

        if (! $request->user() || ! $request->user()->hasAppPermission($app, $level)) {
           return redirect()->back()->with('permission_error', 'คุณไม่มีสิทธิ์เข้าถึงหรือจัดการข้อมูลในส่วนนี้');
        }

        return $next($request);
    }
}
