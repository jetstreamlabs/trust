<?php

namespace Jetlabs\Trust\Models;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Jetlabs\Trust\Traits\TrustRoleTrait;
use Jetlabs\Trust\Contracts\TrustRoleInterface;

class TrustRole extends Model implements TrustRoleInterface
{
    use TrustRoleTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * Creates a new instance of the model.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('trust.tables.roles');
    }
}
