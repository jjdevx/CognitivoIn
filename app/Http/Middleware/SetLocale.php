<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App;
use Config;
use Auth;

class SetLocale
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
        // if (Auth::user() != null)
        // {
        //     $locale = Auth::user()->language;
        // }
        // else if (Session::has('locale'))
        // {
        //     $locale = Session::get('locale', Config::get('app.locale'));
        // }
        // else
        // {
            $locale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
        // }

        App::setLocale($locale);

        return $next($request);
    }
}
