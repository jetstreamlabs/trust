<?php

namespace Jetlabs\Trust\Checkers;

use Illuminate\Support\Facades\Config;
use Jetlabs\Trust\Checkers\Role\TrustRoleQueryChecker;
use Jetlabs\Trust\Checkers\User\TrustUserQueryChecker;
use Jetlabs\Trust\Checkers\Role\TrustRoleDefaultChecker;
use Jetlabs\Trust\Checkers\User\TrustUserDefaultChecker;

class TrustCheckerManager
{
    /**
     * The object in charge of checking the roles and permissions.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Return the right checker according to the configuration.
     *
     * @return \Jetlabs\Trust\Checkers\TrustChecker
     */
    public function getUserChecker()
    {
        switch (Config::get('trust.checker', 'default')) {
            case 'default':
                return new TrustUserDefaultChecker($this->model);
            case 'query':
                return new TrustUserQueryChecker($this->model);
        }
    }

    /**
     * Return the right checker according to the configuration.
     *
     * @return \Jetlabs\Trust\Checkers\TrustChecker
     */
    public function getRoleChecker()
    {
        switch (Config::get('trust.checker', 'default')) {
            case 'default':
                return new TrustRoleDefaultChecker($this->model);
            case 'query':
                return new TrustRoleQueryChecker($this->model);
        }
    }
}
