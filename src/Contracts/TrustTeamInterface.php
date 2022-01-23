<?php

namespace Jetlabs\Trust\Contracts;

interface TrustTeamInterface
{
	/**
	 * Morph by Many relationship between the role and the one of the possible user models.
	 *
	 * @param  string  $relationship
	 * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
	 */
	public function getMorphByUserRelation($relationship);
}
