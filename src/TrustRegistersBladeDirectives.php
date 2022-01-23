<?php

namespace Trust;

use Illuminate\Support\Facades\Blade;

/**
 * This class is the one in charge of registering
 * the blade directives making a difference
 * between the version 5.2 and 5.3
 */
class TrustRegistersBladeDirectives
{
    /**
     * Handles the registration of the blades directives.
     *
     * @param  string  $laravelVersion
     * @return void
     */
    public function handle($laravelVersion = '5.3.0')
    {
        if (version_compare(strtolower($laravelVersion), '5.3.0-dev', '>=')) {
            $this->registerWithParenthesis();
        } else {
            $this->registerWithoutParenthesis();
        }

        $this->registerClosingDirectives();
    }

    /**
     * Registers the directives with parenthesis.
     *
     * @return void
     */
    protected function registerWithParenthesis()
    {
        // Call to Trust::hasRole.
        Blade::directive('role', function ($expression) {
            return "<?php if (app('trust')->hasRole({$expression})) : ?>";
        });

        // Call to Trust::permission.
        Blade::directive('permission', function ($expression) {
            return "<?php if (app('trust')->isAbleTo({$expression})) : ?>";
        });

        // Call to Trust::ability.
        Blade::directive('ability', function ($expression) {
            return "<?php if (app('trust')->ability({$expression})) : ?>";
        });

        // Call to Trust::isAbleToAndOwns.
        Blade::directive('isAbleToAndOwns', function ($expression) {
            return "<?php if (app('trust')->isAbleToAndOwns({$expression})) : ?>";
        });

        // Call to Trust::hasRoleAndOwns.
        Blade::directive('hasRoleAndOwns', function ($expression) {
            return "<?php if (app('trust')->hasRoleAndOwns({$expression})) : ?>";
        });
    }

    /**
     * Registers the directives without parenthesis.
     *
     * @return void
     */
    protected function registerWithoutParenthesis()
    {
        // Call to Trust::hasRole.
        Blade::directive('role', function ($expression) {
            return "<?php if (app('trust')->hasRole{$expression}) : ?>";
        });

        // Call to Trust::isAbleTo.
        Blade::directive('permission', function ($expression) {
            return "<?php if (app('trust')->isAbleTo{$expression}) : ?>";
        });

        // Call to Trust::ability.
        Blade::directive('ability', function ($expression) {
            return "<?php if (app('trust')->ability{$expression}) : ?>";
        });

        // Call to Trust::isAbleToAndOwns.
        Blade::directive('isAbleToAndOwns', function ($expression) {
            return "<?php if (app('trust')->isAbleToAndOwns{$expression}) : ?>";
        });

        // Call to Trust::hasRoleAndOwns.
        Blade::directive('hasRoleAndOwns', function ($expression) {
            return "<?php if (app('trust')->hasRoleAndOwns{$expression}) : ?>";
        });
    }

    /**
     * Registers the closing directives.
     *
     * @return void
     */
    protected function registerClosingDirectives()
    {
        Blade::directive('endrole', function () {
            return "<?php endif; // app('trust')->hasRole ?>";
        });

        Blade::directive('endpermission', function () {
            return "<?php endif; // app('trust')->permission ?>";
        });

        Blade::directive('endability', function () {
            return "<?php endif; // app('trust')->ability ?>";
        });

        Blade::directive('endOwns', function () {
            return "<?php endif; // app('trust')->hasRoleAndOwns or isAbleToAndOwns ?>";
        });
    }
}
