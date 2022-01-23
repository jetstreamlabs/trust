<?php

namespace Jetlabs\Trust\Traits;

use Illuminate\Support\Facades\Config;

trait TrustPermissionTrait
{
	use TrustDynamicUserRelationsCalls;

	/**
	 * Boots the permission model and attaches event listener to
	 * remove the many-to-many records when trying to delete.
	 * Will NOT delete any records if the permission model uses soft deletes.
	 *
	 * @return void|bool
	 */
	public static function bootTrustPermissionTrait()
	{
		static::deleting(function ($permission) {
			if (! method_exists(Config::get('trust.models.permission'), 'bootSoftDeletes')) {
				$permission->roles()->sync([]);
			}
		});

		static::deleting(function ($permission) {
			if (method_exists($permission, 'bootSoftDeletes') && ! $permission->forceDeleting) {
				return;
			}

			$permission->roles()->sync([]);

			foreach (array_keys(Config::get('trust.user_models')) as $key) {
				$permission->$key()->sync([]);
			}
		});
	}

	/**
	 * Many-to-Many relations with role model.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function roles()
	{
		return $this->belongsToMany(
			Config::get('trust.models.role'),
			Config::get('trust.tables.permission_role'),
			Config::get('trust.foreign_keys.permission'),
			Config::get('trust.foreign_keys.role')
		);
	}

	/**
	 * Morph by Many relationship between the permission and the one of the possible user models.
	 *
	 * @param  string  $relationship
	 * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
	 */
	public function getMorphByUserRelation($relationship)
	{
		return $this->morphedByMany(
			Config::get('trust.user_models')[$relationship],
			'user',
			Config::get('trust.tables.permission_user'),
			Config::get('trust.foreign_keys.permission'),
			Config::get('trust.foreign_keys.user')
		);
	}
}
