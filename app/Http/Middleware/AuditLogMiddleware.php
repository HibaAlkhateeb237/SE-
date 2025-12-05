<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        // لا تنسى أن تخزن بيانات ملائمة في audit_logs
        \App\Models\AuditLog::create([
            'actor_type' => $request->user() ? get_class($request->user()) : null,
            'actor_id' => $request->user()->id ?? null,
            'action' => $request->method().' '.$request->path(),
            'description' => null,
            'meta' => json_encode(['ip'=>$request->ip()]),
        ]);
        return $response;
    }

}
