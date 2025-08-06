<?php

namespace BoukjijTarik\WooRoleManager\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission, string ...$permissions): Response
    {
        $permissions = array_merge([$permission], $permissions);

        if (!$request->user() || !$request->user()->hasAnyPermission($permissions)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
} 