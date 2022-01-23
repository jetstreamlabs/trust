<?php

namespace Jetlabs\Trust\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Jetlabs\Trust\Traits\TrustUserTrait;
use Traitor\Traitor;

class AddTrustUserTraitUseCommand extends Command
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'trust:add-trait';

	/**
	 * Trait added to User model.
	 *
	 * @var string
	 */
	protected $targetTrait = TrustUserTrait::class;

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$models = $this->getUserModels();

		foreach ($models as $model) {
			if (! class_exists($model)) {
				$this->error("Class $model does not exist.");

				return;
			}

			if ($this->alreadyUsesTrustUserTrait($model)) {
				$this->error("Class $model already uses TrustUserTrait.");
				continue;
			}

			Traitor::addTrait($this->targetTrait)->toClass($model);
		}

		$this->info("TrustUserTrait added successfully to {$models->implode(', ')}");
	}

	/**
	 * Check if the class already uses TrustUserTrait.
	 *
	 * @param  string  $model
	 * @return bool
	 */
	protected function alreadyUsesTrustUserTrait($model)
	{
		return in_array(TrustUserTrait::class, class_uses($model));
	}

	/**
	 * Get the description of which clases the TrustUserTrait was added.
	 *
	 * @return string
	 */
	public function getDescription()
	{
		return "Add TrustUserTrait to {$this->getUserModels()->implode(', ')} class";
	}

	/**
	 * Return the User models array.
	 *
	 * @return array
	 */
	protected function getUserModels()
	{
		return new Collection(Config::get('trust.user_models', []));
	}
}
