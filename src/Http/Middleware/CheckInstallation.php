<?php

namespace Bijanbiria\LaravelDeployWizard\Http\Middleware;

use Closure;
use Bijanbiria\LaravelDeployWizard\DeployWizard;
use Illuminate\Http\Request;

class CheckInstallation
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {
        if (!DeployWizard::isInstalled()) {
            return redirect('/deploy-wizard');
        }

        return $next($request);
    }
}