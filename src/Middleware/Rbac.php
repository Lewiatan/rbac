<?php namespace Lewiatan\Rbac\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Laracasts\Flash\Flash;

class Rbac {

	protected $auth;

	protected $redirectTo = '/';
	protected $redirectToRoute = null;

	protected static $permissions = [];

	/**
	 * @param Guard $auth
     */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$this->checkForErrors();

		$routeName = $request->route()->getName();

		if ($this->checkPermissions($routeName)) {
			return $next($request);
		}

        Flash::error('Nie masz wystarczających uprawnień robaczku.');

		return $this->redirect();

	}

	/**
	 * @param $permission
	 * @return mixed
	 */
	protected function checkPermissions($routeName) {
		$permission = array_get(static::$permissions, $routeName, false);

		if (! $permission) return false;

        if ($permission instanceof Closure) return $permission();

		return $this->auth->user()->can($permission);
	}

	/**
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	protected function redirect() {
		if ($this->redirectToRoute) {
			return $this->redirectToRoute();
		}

		return redirect($this->redirectTo);
	}

	protected function arePermissionsSet() {
		return ! empty(static::$permissions);
	}

	protected function checkForErrors() {
		if (! $this->arePermissionsSet()) throw new InvalidPermissionsException;
	}

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToRoute() {
        if (is_array($this->redirectToRoute)) {
            return redirect()->route($this->redirectToRoute[0], $this->redirectToRoute[1]);
        }

        return redirect()->route($this->redirectToRoute);
    }

}
