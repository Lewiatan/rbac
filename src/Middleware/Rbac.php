<?php namespace Lewiatan\Rbac\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Rbac {

	protected $auth;

	protected $route = null;
	protected $redirect = '/';


	/**
	 * @param Guard $auth
     */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	private static $permissions = [];

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (empty(static::$permissions)) return $next($request);

		$routeName = $request->route()->getName();
		$permission = static::$permissions[$routeName];

		if ($this->auth->user()->can($permission)) {
			return $next($request);
		}

		if ($this->route) {
			return redirect()->route($this->route);
		}

		return redirect($this->redirect);

	}

}
