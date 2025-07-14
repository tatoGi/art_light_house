<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (! in_array($request->segment(1), config('app.locales'))) {

            $fallback = session('locale') ?: config('app.fallback_locale');

            return redirect()->to('/'.$fallback);
        }

        session(['locale' => $request->segment(1)]);
        app()->setLocale($request->segment(1));

        $request->route()->forgetParameter('locale');

        return $next($request);
    }
}
