<?php namespace Viraj\Hawkeye;

use Illuminate\Support\Facades\Facade;

class HawkeyeFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'hawkeye';
    }
}