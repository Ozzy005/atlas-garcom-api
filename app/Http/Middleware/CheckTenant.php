<?php

namespace App\Http\Middleware;

use App\Traits\Tenant;
use Closure;
use Illuminate\Http\Request;

class CheckTenant
{
    use Tenant;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->isTenant()) {
            abort(403, 'Você precisa ser contratante para acessar essa funcionalidade!');
        }

        return $next($request);
    }
}
