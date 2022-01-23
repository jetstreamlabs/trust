<?php

namespace Jetlabs\Trust\Checkers\Role;

use Illuminate\Database\Eloquent\Model;

abstract class TrustRoleChecker
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $role;

    public function __construct(Model $role)
    {
        $this->role = $role;
    }

    abstract public function currentRoleHasPermission($permission, $requireAll = false);

    abstract public function currentRoleFlushCache();
}
