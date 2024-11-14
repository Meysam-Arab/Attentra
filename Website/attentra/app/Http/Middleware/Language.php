<?php


// app/Http/Middleware/Language.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class Language {


    protected $languages = ['en','pr'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Session::has('applocale') AND array_key_exists(Session::get('applocale'), Config::get('languages'))) {
            App::setLocale(Session::get('applocale'));
        }
        else { // This is optional as Laravel will automatically set the fallback language if there is none specified
            App::setLocale(Config::get('app.fallback_locale'));
        }
        return $next($request);
    }


//    public function handle($request, Closure $next)
//    {
//        if (Session::has('applocale') AND array_key_exists(Session::get('applocale'), Config::get('languages'))) {
//            App::setLocale(Session::get('applocale'));
//        }
//        else { // This is optional as Laravel will automatically set the fallback language if there is none specified
//            App::setLocale(Config::get('app.fallback_locale'));
//        }
//        return $next($request);
//    }

//    public function __construct(Application $app, Redirector $redirector, Request $request) {
//        $this->app = $app;
//        $this->redirector = $redirector;
//        $this->request = $request;
//    }
    /**
     * The availables languages.
     *
     * @array $languages
     */
//    protected $languages = ['en','pr'];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */





//    public function handle($request, Closure $next)
//    {
//
////        if(!Session::has('locale'))
////        {
////            Session::put('locale', $request->getPreferredLanguage($this->languages));
////        }
////
////        app()->setLocale(Session::get('locale'));
////
////        if(!Session::has('statut'))
////        {
////            Session::put('statut', Auth::check() ?  Auth::user()->role->slug : 'visitor');
////        }
//        if(!session()->has('locale'))
//        {
//            session()->put('locale', $request->getPreferredLanguage($this->languages));
//        }
//
//        app()->setLocale(session()->get('locale'));
//
////        if(!session()->has('statut'))
////        {
////            session()->put('statut', Auth::check() ?  Auth::user()->role->slug : 'visitor');
////        }
//
//        // Make sure current locale exists.
////        $locale = $request->segment(1);
////
////        if ( ! array_key_exists($locale, $this->app->config->get('app.locales'))) {
////            $segments = $request->segments();
////            $segments[0] = $this->app->config->get('app.fallback_locale');
////
////            return $this->redirector->to(implode('/', $segments));
////        }
////
////        $this->app->setLocale($locale);
//
//        return $next($request);
//    }

}