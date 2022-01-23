<?php

namespace Jetlabs\Trust\Traits;

use Jetlabs\Trust\Helper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Jetlabs\Trust\Checkers\TrustCheckerManager;

trait TrustRoleTrait
{
    use TrustDynamicUserRelationsCalls;
    use TrustHasEvents;

    /**
     * Boots the role model and attaches event listener to
     * remove the many-to-many records when trying to delete.
     * Will NOT delete any records if the role model uses soft deletes.
     *
     * @return void|bool
     */
    public static function bootTrustRoleTrait()
    {
        $flushCache = function ($role) {
            $role->flushCache();
        };

        // If the role doesn't use SoftDeletes.
        if (method_exists(static::class, 'restored')) {
            static::restored($flushCache);
        }

        static::deleted($flushCache);
        static::saved($flushCache);

        static::deleting(function ($role) {
            if (method_exists($role, 'bootSoftDeletes') && !$role->forceDeleting) {
                return;
            }

            $role->permissions()->sync([]);

            foreach (array_keys(Config::get('trust.user_models')) as $key) {
                $role->$key()->sync([]);
            }
        });
    }

    /**
     * Return the right checker for the role model.
     *
     * @return \Jetlabs\Trust\Checkers\Role\TrustRoleChecker
     */
    protected function laratrustRoleChecker()
    {
        return (new TrustCheckerManager($this))->getRoleChecker();
    }

    /**
     * Morph by Many relationship between the role and the one of the possible user models.
     *
     * @param  string  $relationship
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function getMorphByUserRelation($relationship)
    {
        return $this->morphedByMany(
            Config::get('trust.user_models')[$relationship],
            'user',
            Config::get('trust.tables.role_user'),
            Config::get('trust.foreign_keys.role'),
            Config::get('trust.foreign_keys.user')
        );
    }

    /**
     * Many-to-Many relations with the permission model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(
            Config::get('trust.models.permission'),
            Config::get('trust.tables.permission_role'),
            Config::get('trust.foreign_keys.role'),
            Config::get('trust.foreign_keys.permission')
        );
    }

    /**
     * Checks if the role has a permission by its name.
     *
     * @param  string|array  $permission       Permission name or array of permission names.
     * @param  bool  $requireAll       All permissions in the array are required.
     * @return bool
     */
    public function hasPermission($permission, $requireAll = false)
    {
        return $this->laratrustRoleChecker($this)
            ->currentRoleHasPermission($permission, $requireAll);
    }

    /**
     * Save the inputted permissions.
     *
     * @param  mixed  $permissions
     * @return array
     */
    public function syncPermissions($permissions)
    {
        $mappedPermissions = [];

        foreach ($permissions as $permission) {
            $mappedPermissions[] = Helper::getIdFor($permission, 'permission');
        }

        $changes = $this->permissions()->sync($mappedPermissions);
        $this->flushCache();
        $this->fireTrustEvent("permission.synced", [$this, $changes]);

        return $this;
    }

    /**
     * Attach permission to current role.
     *
     * @param  object|array  $permission
     * @return void
     */
    public function attachPermission($permission)
    {
        $permission = Helper::getIdFor($permission, 'permission');

        $this->permissions()->attach($permission);
        $this->flushCache();
        $this->fireTrustEvent("permission.attached", [$this, $permission]);

        return $this;
    }

    /**
     * Detach permission from current role.
     *
     * @param  object|array  $permission
     * @return void
     */
    public function detachPermission($permission)
    {
        $permission = Helper::getIdFor($permission, 'permission');

        $this->permissions()->detach($permission);
        $this->flushCache();
        $this->fireTrustEvent("permission.detached", [$this, $permission]);

        return $this;
    }

    /**
     * Attach multiple permissions to current role.
     *
     * @param  mixed  $permissions
     * @return void
     */
    public function attachPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            $this->attachPermission($permission);
        }

        return $this;
    }

    /**
     * Detach multiple permissions from current role
     *
     * @param  mixed  $permissions
     * @return void
     */
    public function detachPermissions($permissions = null)
    {
        if (!$permissions) {
            $permissions = $this->permissions()->get();
        }

        foreach ($permissions as $permission) {
            $this->detachPermission($permission);
        }

        return $this;
    }

    /**
     * Flush the role's cache.
     *
     * @return void
     */
    public function flushCache()
    {
        return $this->laratrustRoleChecker()->currentRoleFlushCache();
    }
}
