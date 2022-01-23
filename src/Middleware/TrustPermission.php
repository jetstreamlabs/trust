<?php

namespace Jetlabs\Trust\Middleware;

use Closure;

class TrustPermission extends TrustMiddleware
{
	/**
	 * Handle incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  Closure  $next
	 * @param  string  $permissions
	 * @param  string|null  $team
	 * @param  string|null  $options
	 * @return mixed
	 */
	public function handle($request, Closure $next, $permissions, $team = null, $options = '')
	{
		if (! $this->authorization('permissions', $permissions, $team, $options)) {
			return $this->unauthorized();
		}

		return $next($request);
	}
}
