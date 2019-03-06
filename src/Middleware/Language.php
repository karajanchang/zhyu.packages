<?php

namespace Zhyu\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class Language
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
        $locale = $request->input('locale');
        if(isset($locale) && strlen($locale)>0){
            Session::put('locale', $locale);
        }
        if (Session::has('locale')) {
            app()->setLocale(Session::get('locale'));
        }
        else { // This is optional as Laravel will automatically set the fallback language if there is none specified
            app()->setLocale(Config::get('app.fallback_locale'));
        }
        return $next($request);
    }
}
