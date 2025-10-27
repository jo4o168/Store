<?php

namespace App\Providers;

use App\Enum\Permissions;
use Illuminate\Auth\Access\Response;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        {
            RateLimiter::for('api', function (Request $request) {
                return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
            });

            $permissions = Permissions::cases();

            foreach ($permissions as $permission) {
                Gate::define($permission->value, function () use ($permission) {
                    if (Auth::user()->master) {
                        return Response::allow();
                    }

                    $userPermissions = array_merge(...Auth::user()->userGroups()->pluck('permissions'));
                    $status = in_array($permission->value, (array)$userPermissions);
                    return $status
                        ? Response::allow()
                        : Response::denyWithStatus(403);
                });
            }
        }
    }
}
