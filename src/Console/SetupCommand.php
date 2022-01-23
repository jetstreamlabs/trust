<?php

namespace Jetlabs\Trust\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class SetupCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'trust:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup migration and models for Trust';

    /**
     * Commands to call with their description.
     *
     * @var array
     */
    protected $calls = [
        'trust:migration' => 'Creating migration',
        'trust:role' => 'Creating Role model',
        'trust:permission' => 'Creating Permission model',
        'trust:add-trait' => 'Adding TrustUserTrait to User model'
    ];

    /**
     * Create a new command instance
     *
     * @return void
     */
    public function __construct()
    {
        if (Config::get('trust.teams.enabled')) {
            $this->calls['trust:team'] = 'Creating Team model';
        }

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->calls as $command => $info) {
            $this->line(PHP_EOL . $info);
            $this->call($command);
        }
    }
}
