<?php

namespace Jetlabs\Trust\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Jetlabs\Trust\Contracts\TrustPermissionInterface;
use Jetlabs\Trust\Traits\TrustPermissionTrait;

class TrustPermission extends Model implements TrustPermissionInterface
{
	use TrustPermissionTrait;

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
		$this->table = Config::get('trust.tables.permissions');
	}
}
