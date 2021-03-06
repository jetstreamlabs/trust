<?php

namespace Jetlabs\Trust\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class MigrationCommand extends Command
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'trust:migration';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates a migration following the Trust specifications.';

	/**
	 * Suffix of the migration name.
	 *
	 * @var string
	 */
	protected $migrationSuffix = 'laratrust_setup_tables';

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$this->line('');
		$this->info('Trust Migration Creation.');
		if (Config::get('trust.teams.enabled')) {
			$this->comment('You are using the teams feature.');
		}
		$this->line('');
		$this->comment($this->generateMigrationMessage());

		$existingMigrations = $this->alreadyExistingMigrations();
		$defaultAnswer = true;

		if ($existingMigrations) {
			$this->line('');

			$this->warn($this->getExistingMigrationsWarning($existingMigrations));

			$defaultAnswer = false;
		}

		$this->line('');

		if (! $this->confirm('Proceed with the migration creation?', $defaultAnswer)) {
			return;
		}

		$this->line('');

		$this->line('Creating migration');

		if ($this->createMigration()) {
			$this->info('Migration created successfully.');
		} else {
			$this->error(
				"Couldn't create migration.\n".
				'Check the write permissions within the database/migrations directory.'
			);
		}

		$this->line('');
	}

	/**
	 * Create the migration.
	 *
	 * @return bool
	 */
	protected function createMigration()
	{
		$migrationPath = $this->getMigrationPath();

		$output = $this->laravel->view
			->make('trust::migration')
			->with(['trust' => Config::get('trust')])
			->render();

		if (! file_exists($migrationPath) && $fs = fopen($migrationPath, 'x')) {
			fwrite($fs, $output);
			fclose($fs);

			return true;
		}

		return false;
	}

	/**
	 * Generate the message to display when running the
	 * console command showing what tables are going
	 * to be created.
	 *
	 * @return string
	 */
	protected function generateMigrationMessage()
	{
		$tables = Collection::make(Config::get('trust.tables'))
			->reject(function ($value, $key) {
				return $key == 'teams' && ! Config::get('trust.teams.enabled');
			})
			->sort();

		return "A migration that creates {$tables->implode(', ')} "
			.'tables will be created in database/migrations directory';
	}

	/**
	 * Build a warning regarding possible duplication
	 * due to already existing migrations.
	 *
	 * @param  array  $existingMigrations
	 * @return string
	 */
	protected function getExistingMigrationsWarning(array $existingMigrations)
	{
		if (count($existingMigrations) > 1) {
			$base = "Trust migrations already exist.\nFollowing files were found: ";
		} else {
			$base = "Trust migration already exists.\nFollowing file was found: ";
		}

		return $base.array_reduce($existingMigrations, function ($carry, $fileName) {
			return $carry."\n - ".$fileName;
		});
	}

	/**
	 * Check if there is another migration
	 * with the same suffix.
	 *
	 * @return array
	 */
	protected function alreadyExistingMigrations()
	{
		$matchingFiles = glob($this->getMigrationPath('*'));

		return array_map(function ($path) {
			return basename($path);
		}, $matchingFiles);
	}

	/**
	 * Get the migration path.
	 *
	 * The date parameter is optional for ability
	 * to provide a custom value or a wildcard.
	 *
	 * @param  string|null  $date
	 * @return string
	 */
	protected function getMigrationPath($date = null)
	{
		$date = $date ?: date('Y_m_d_His');

		return database_path("migrations/${date}_{$this->migrationSuffix}.php");
	}
}
