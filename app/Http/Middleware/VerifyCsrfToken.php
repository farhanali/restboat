<?php namespace Restboat\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        $subDomain = explode('.', $request->server('HTTP_HOST'))[0];

        if ($subDomain == 'mock')
        {
            return $next($request);
        }

        return parent::handle($request, $next);
	}

}
