<?php

namespace App\Http\Middleware;

use Closure;

class FiltroPorArea
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $areas = array_except(func_get_args(), [0,1]);
        foreach ($areas as $key => $value) {
            if ($request->user()->UserInfo->area->desc == $value || $request->user()->is("developer") || $request->user()->is("administracion") ){
                return $next($request);
            }
        }
        return redirect('/');
    }
}
