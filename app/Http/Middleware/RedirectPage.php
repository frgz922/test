<?php

namespace App\Http\Middleware;

use App\Urls;
use App\UrlsAliases;
use Closure;

class RedirectPage
{
    /**
     * Handle an incoming request.
     * Redirects to the original site given a shortened URL.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $alias_query = UrlsAliases::where('alias', $request->alias);

        if ($alias_query->exists()) {
            $alias = $alias_query->with('url')->first();
            $url = $alias->url->url;

            $url_visit_count_update = Urls::find($alias->url_id);
            $url_visit_count_update->visit_count = $alias->url->visit_count + 1;

            $url_visit_count_update->save();

            return redirect($url);
        }
        return $next($request);
    }
}
