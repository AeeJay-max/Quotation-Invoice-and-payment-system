<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        // Admins bypass ALL gate checks — full access always
        Gate::before(function ($user, $ability) {
            if ($user->is_admin || $user->role_id == 1) {
                return true;
            }
        });

        Gate::define('create', function ($user, $model) {
            $role = Auth::user()->role;
            if (!$role || empty($role->permission)) {
                return false;
            }
            $permission = $role->permission;
            $permissions = $permission->permission;
            if (array_key_exists($model, $permissions)) {
                if (isset($permissions[$model]['create'])) {
                    return true;
                }
                return false;
            }
            return false;
        });

        Gate::define('read', function ($user, $model) {
            $role = Auth::user()->role;
            if (!$role || empty($role->permission)) {
                return false;
            }
            $permission = $role->permission;
            $permissions = $permission->permission;
            if (array_key_exists($model, $permissions)) {
                if (isset($permissions[$model]['read'])) {
                    return true;
                }
                return false;
            }
            return false;
        });

        Gate::define('update', function ($user, $model) {
            $role = Auth::user()->role;
            if (!$role || empty($role->permission)) {
                return false;
            }
            $permission = $role->permission;
            $permissions = $permission->permission;
            if (array_key_exists($model, $permissions)) {
                if (isset($permissions[$model]['update'])) {
                    return true;
                }
                return false;
            }
            return false;
        });

        Gate::define('delete', function ($user, $model) {
            $role = Auth::user()->role;
            if (!$role || empty($role->permission)) {
                return false;
            }
            $permission = $role->permission;
            $permissions = $permission->permission;
            if (array_key_exists($model, $permissions)) {
                if (isset($permissions[$model]['delete'])) {
                    return true;
                }
                return false;
            }
            return false;
        });

        Gate::define('list', function ($user, $model) {
            $role = Auth::user()->role;
            if (!$role || empty($role->permission)) {
                return false;
            }
            $permission = $role->permission;
            $permissions = $permission->permission;
            if (array_key_exists($model, $permissions)) {
                if (isset($permissions[$model]['list'])) {
                    return true;
                }
                return false;
            }
            return false;
        });
    }
}
