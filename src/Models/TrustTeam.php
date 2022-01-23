<?php

namespace Jetlabs\Trust\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Jetlabs\Trust\Contracts\TrustTeamInterface;
use Jetlabs\Trust\Traits\TrustTeamTrait;

class TrustTeam extends Model implements TrustTeamInterface
{
	use TrustTeamTrait;

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
		$this->table = Config::get('trust.tables.teams');
	}
}
