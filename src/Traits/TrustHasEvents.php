<?php

namespace Jetlabs\Trust\Traits;

use Illuminate\Support\Str;

trait TrustHasEvents
{
	protected static $laratrustObservables = [
		'roleAttached',
		'roleDetached',
		'permissionAttached',
		'permissionDetached',
		'roleSynced',
		'permissionSynced',
	];

	/**
	 * Register an observer to the Trust events.
	 *
	 * @param  object|string  $class
	 * @return void
	 */
	public static function laratrustObserve($class)
	{
		$className = is_string($class) ? $class : get_class($class);

		foreach (self::$laratrustObservables as $event) {
			if (method_exists($class, $event)) {
				static::registerTrustEvent(Str::snake($event, '.'), $className.'@'.$event);
			}
		}
	}

	public static function laratrustFlushObservables()
	{
		foreach (self::$laratrustObservables as $event) {
			$event = Str::snake($event, '.');
			static::$dispatcher->forget("trust.{$event}: ".static::class);
		}
	}

	/**
	 * Fire the given event for the model.
	 *
	 * @param  string  $event
	 * @param  array  $payload
	 * @return mixed
	 */
	protected function fireTrustEvent($event, array $payload)
	{
		if (! isset(static::$dispatcher)) {
			return true;
		}

		return static::$dispatcher->dispatch(
			"trust.{$event}: ".static::class,
			$payload
		);
	}

	/**
	 * Register a trust event with the dispatcher.
	 *
	 * @param  string  $event
	 * @param  \Closure|string  $callback
	 * @return void
	 */
	public static function registerTrustEvent($event, $callback)
	{
		if (isset(static::$dispatcher)) {
			$name = static::class;

			static::$dispatcher->listen("trust.{$event}: {$name}", $callback);
		}
	}

	/**
	 * Register a role attached trust event with the dispatcher.
	 *
	 * @param  \Closure|string  $callback
	 * @return void
	 */
	public static function roleAttached($callback)
	{
		static::registerTrustEvent('role.attached', $callback);
	}

	/**
	 * Register a role detached trust event with the dispatcher.
	 *
	 * @param  \Closure|string  $callback
	 * @return void
	 */
	public static function roleDetached($callback)
	{
		static::registerTrustEvent('role.detached', $callback);
	}

	/**
	 * Register a permission attached trust event with the dispatcher.
	 *
	 * @param  \Closure|string  $callback
	 * @return void
	 */
	public static function permissionAttached($callback)
	{
		static::registerTrustEvent('permission.attached', $callback);
	}

	/**
	 * Register a permission detached trust event with the dispatcher.
	 *
	 * @param  \Closure|string  $callback
	 * @return void
	 */
	public static function permissionDetached($callback)
	{
		static::registerTrustEvent('permission.detached', $callback);
	}

	/**
	 * Register a role synced trust event with the dispatcher.
	 *
	 * @param  \Closure|string  $callback
	 * @return void
	 */
	public static function roleSynced($callback)
	{
		static::registerTrustEvent('role.synced', $callback);
	}

	/**
	 * Register a permission synced trust event with the dispatcher.
	 *
	 * @param  \Closure|string  $callback
	 * @return void
	 */
	public static function permissionSynced($callback)
	{
		static::registerTrustEvent('permission.synced', $callback);
	}
}
