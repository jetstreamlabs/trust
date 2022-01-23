<?php

return [
	/*
	|--------------------------------------------------------------------------
	| Use MorphMap in relationships between models
	|--------------------------------------------------------------------------
	|
	| If true, the morphMap feature is going to be used. The array values that
	| are going to be used are the ones inside the 'user_models' array.
	|
	*/
	'use_morph_map' => false,

	/*
	|--------------------------------------------------------------------------
	| Which permissions and role checker to use.
	|--------------------------------------------------------------------------
	|
	| Defines if you want to use the roles and permissions checker.
	| Available:
	| - default: Check for the roles and permissions using the method that Trust
				 has always used.
	| - query: Check for the roles and permissions using direct queries to the database.
	|           This method doesn't support cache yet.
	|
	 */
	'checker' => 'default',

	/*
	|--------------------------------------------------------------------------
	| Cache
	|--------------------------------------------------------------------------
	|
	| Manage Trust's cache configurations. It uses the driver defined in the
	| config/cache.php file.
	|
	*/
	'cache' => [
		/*
		|--------------------------------------------------------------------------
		| Use cache in the package
		|--------------------------------------------------------------------------
		|
		| Defines if Trust will use Laravel's Cache to cache the roles and permissions.
		| NOTE: Currently the database check does not use cache.
		|
		*/
		'enabled' => env('TRUST_ENABLE_CACHE', true),

		/*
		|--------------------------------------------------------------------------
		| Time to store in cache Trust's roles and permissions.
		|--------------------------------------------------------------------------
		|
		| Determines the time in SECONDS to store Trust's roles and permissions in the cache.
		|
		*/
		'expiration_time' => 3600,
	],

	/*
	|--------------------------------------------------------------------------
	| Trust User Models
	|--------------------------------------------------------------------------
	|
	| This is the array that contains the information of the user models.
	| This information is used in the add-trait command, for the roles and
	| permissions relationships with the possible user models, and the
	| administration panel to attach roles and permissions to the users.
	|
	| The key in the array is the name of the relationship inside the roles and permissions.
	|
	*/
	'user_models' => [
		'users' => \App\Models\User::class,
	],

	/*
	|--------------------------------------------------------------------------
	| Trust Models
	|--------------------------------------------------------------------------
	|
	| These are the models used by Trust to define the roles, permissions and teams.
	| If you want the Trust models to be in a different namespace or
	| to have a different name, you can do it here.
	|
	*/
	'models' => [

		'role' => \App\Models\Role::class,

		'permission' => \App\Models\Permission::class,

		/**
		 * Will be used only if the teams functionality is enabled.
		 */
		'team' => \App\Models\Team::class,
	],

	/*
	|--------------------------------------------------------------------------
	| Trust Tables
	|--------------------------------------------------------------------------
	|
	| These are the tables used by Trust to store all the authorization data.
	|
	*/
	'tables' => [

		'roles' => 'roles',

		'permissions' => 'permissions',

		/**
		 * Will be used only if the teams functionality is enabled.
		 */
		'teams' => 'teams',

		'role_user' => 'role_user',

		'permission_user' => 'permission_user',

		'permission_role' => 'permission_role',
	],

	/*
	|--------------------------------------------------------------------------
	| Trust Foreign Keys
	|--------------------------------------------------------------------------
	|
	| These are the foreign keys used by Trust in the intermediate tables.
	|
	*/
	'foreign_keys' => [
		/**
		 * User foreign key on Trust's role_user and permission_user tables.
		 */
		'user' => 'user_id',

		/**
		 * Role foreign key on Trust's role_user and permission_role tables.
		 */
		'role' => 'role_id',

		/**
		 * Role foreign key on Trust's permission_user and permission_role tables.
		 */
		'permission' => 'permission_id',

		/**
		 * Role foreign key on Trust's role_user and permission_user tables.
		 */
		'team' => 'team_id',
	],

	/*
	|--------------------------------------------------------------------------
	| Trust Middleware
	|--------------------------------------------------------------------------
	|
	| This configuration helps to customize the Trust middleware behavior.
	|
	*/
	'middleware' => [
		/**
		 * Define if the Trust middleware are registered automatically in the service provider.
		 */
		'register' => true,

		/**
		 * Method to be called in the middleware return case.
		 * Available: abort|redirect.
		 */
		'handling' => 'abort',

		/**
		 * Handlers for the unauthorized method in the middlewares.
		 * The name of the handler must be the same as the handling.
		 */
		'handlers' => [
			/**
			 * Aborts the execution with a 403 code and allows you to provide the response text.
			 */
			'abort' => [
				'code' => 403,
				'message' => 'User does not have any of the necessary access rights.',
			],

			/**
			 * Redirects the user to the given url.
			 * If you want to flash a key to the session,
			 * you can do it by setting the key and the content of the message
			 * If the message content is empty it won't be added to the redirection.
			 */
			'redirect' => [
				'url' => '/home',
				'message' => [
					'key' => 'error',
					'content' => '',
				],
			],
		],
	],

	'teams' => [
		/*
		|--------------------------------------------------------------------------
		| Use teams feature in the package
		|--------------------------------------------------------------------------
		|
		| Defines if Trust will use the teams feature.
		| Please check the docs to see what you need to do in case you have the package already configured.
		|
		*/
		'enabled' => false,

		/*
		|--------------------------------------------------------------------------
		| Strict check for roles/permissions inside teams
		|--------------------------------------------------------------------------
		|
		| Determines if a strict check should be done when checking if a role or permission
		| is attached inside a team.
		| If it's false, when checking a role/permission without specifying the team,
		| it will check only if the user has attached that role/permission ignoring the team.
		|
		*/
		'strict_check' => false,
	],

	/*
	|--------------------------------------------------------------------------
	| Trust Magic 'isAbleTo' Method
	|--------------------------------------------------------------------------
	|
	| Supported cases for the magic is able to method (Refer to the docs).
	| Available: camel_case|snake_case|kebab_case
	|
	*/
	'magic_is_able_to_method_case' => 'kebab_case',

	/*
	|--------------------------------------------------------------------------
	| Trust Permissions as Gates
	|--------------------------------------------------------------------------
	|
	| Determines if you can check if a user has a permission using the "can" method.
	|
	*/
	'permissions_as_gates' => false,
];
