<?php

namespace Jetlabs\Trust\Middleware;

use Closure;

class TrustRole extends TrustMiddleware
{
	/**
	 * Handle incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  Closure  $next
	 * @param  string  $roles
	 * @param  string|null  $team
	 * @param  string|null  $options
	 * @return mixed
	 */
	public function handle($request, Closure $next, $roles, $team = null, $options = '')
	{
		if (! $this->authorization('roles', $roles, $team, $options)) {
			return $this->unauthorized();
		}

		return $next($request);
	}
}
