<?php

namespace Trust;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Relations\Relation;

class TrustServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->useMorphMapForRelationships();
        $this->registerMiddlewares();
        $this->registerBladeDirectives();
        $this->registerRoutes();
        $this->registerResources();
        $this->registerPermissionsToGate();
        $this->defineAssetPublishing();
    }

    /**
     * If the user wants to use the morphMap it uses the morphMap.
     *
     * @return void
     */
    protected function useMorphMapForRelationships()
    {
        if ($this->app['config']->get('trust.use_morph_map')) {
            Relation::morphMap($this->app['config']->get('trust.user_models'));
        }
    }

    /**
     * Register the middlewares automatically.
     *
     * @return void
     */
    protected function registerMiddlewares()
    {
        if (!$this->app['config']->get('trust.middleware.register')) {
            return;
        }

        $router = $this->app['router'];

        if (method_exists($router, 'middleware')) {
            $registerMethod = 'middleware';
        } elseif (method_exists($router, 'aliasMiddleware')) {
            $registerMethod = 'aliasMiddleware';
        } else {
            return;
        }

        $middlewares = [
            'role' => \Jetlabs\Trust\Middleware\TrustRole::class,
            'permission' => \Jetlabs\Trust\Middleware\TrustPermission::class,
            'ability' => \Jetlabs\Trust\Middleware\TrustAbility::class,
        ];

        foreach ($middlewares as $key => $class) {
            $router->$registerMethod($key, $class);
        }
    }

    /**
     * Register the blade directives.
     *
     * @return void
     */
    private function registerBladeDirectives()
    {
        if (!class_exists('\Blade')) {
            return;
        }

        (new TrustRegistersBladeDirectives)->handle($this->app->version());
    }

    /**
     * Register the routes used by the Trust admin panel.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        if (!$this->app['config']->get('trust.panel.register')) {
            return;
        }

        Route::group([
            'prefix' => config('trust.panel.path'),
            'namespace' => 'Jetlabs\Trust\Http\Controllers',
            'middleware' => config('trust.panel.middleware', 'web'),
        ], function () {
            Route::redirect('/', '/'. config('trust.panel.path'). '/roles-assignment');
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Register all the possible views used by Trust.
     *
     * @return void
     */
    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'trust');
    }

    /**
     * Register permissions to Laravel Gate
     *
     * @return void
     */
    protected function registerPermissionsToGate()
    {
        if (!$this->app['config']->get('trust.permissions_as_gates')) {
            return;
        }

        app(Gate::class)->before(function (Authorizable $user, string $ability) {
            if (method_exists($user, 'hasPermission')) {
                return $user->hasPermission($ability) ?: null;
            }
        });
    }

    /**
     * Register the assets that are publishable for the admin panel to work.
     *
     * @return void
     */
    protected function defineAssetPublishing()
    {
        if (!$this->app['config']->get('trust.panel.register')) {
            return;
        }

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/trust'),
        ], 'trust-assets');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->configure();
        $this->offerPublishing();
        $this->registerTrust();
        $this->registerCommands();
    }

    /**
     * Setup the configuration for Trust.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/trust.php', 'trust');
    }

    /**
     * Setup the resource publishing group for Trust.
     *
     * @return void
     */
    protected function offerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/trust.php' => config_path('trust.php'),
            ], 'trust');

            $this->publishes([
                __DIR__. '/../config/laratrust_seeder.php' => config_path('laratrust_seeder.php'),
            ], 'trust-seeder');
        }
    }

    /**
     * Register the application bindings.
     *
     * @return void
     */
    protected function registerTrust()
    {
        $this->app->bind('trust', function ($app) {
            return new Trust($app);
        });

        $this->app->alias('trust', 'Jetlabs\Trust\Trust');
    }

    /**
     * Register the Trusts commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\AddTrustUserTraitUseCommand::class,
                Console\MakePermissionCommand::class,
                Console\MakeRoleCommand::class,
                Console\MakeSeederCommand::class,
                Console\MakeTeamCommand::class,
                Console\MigrationCommand::class,
                Console\SetupCommand::class,
                Console\SetupTeamsCommand::class,
                Console\UpgradeCommand::class,
            ]);
        }
    }
}
