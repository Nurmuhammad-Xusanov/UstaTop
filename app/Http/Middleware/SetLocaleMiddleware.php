<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocaleMiddleware
{
    /**
     * Supported languages.
     */
    protected array $supportedLocales = ['uz', 'ru', 'en'];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = Session::get('locale');

        if (!$locale || !in_array($locale, $this->supportedLocales)) {
            $locale = $request->cookie('locale');
        }

        if (!$locale || !in_array($locale, $this->supportedLocales)) {
            // Detect from browser Accept-Language
            $browserLang = $request->server('HTTP_ACCEPT_LANGUAGE');
            if ($browserLang) {
                $lang = substr($browserLang, 0, 2);
                if (in_array($lang, $this->supportedLocales)) {
                    $locale = $lang;
                }
            }
        }

        if (!$locale || !in_array($locale, $this->supportedLocales)) {
            $locale = config('app.fallback_locale', 'uz');
        }

        App::setLocale($locale);
        Session::put('locale', $locale);

        return $next($request);
    }
}
