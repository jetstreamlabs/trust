<?php

namespace Trust;

use Illuminate\Support\Facades\Facade;

class TrustFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'trust';
    }
}
