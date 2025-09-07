<?php
/**
 * @license MIT
 *
 * Modified by gravitykit on 22-November-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace GravityKit\GravityFormsElementorWidget\Foundation\ThirdParty\Illuminate\Support\Facades;

use GravityKit\GravityFormsElementorWidget\Foundation\ThirdParty\Illuminate\Support\Testing\Fakes\BusFake;
use GravityKit\GravityFormsElementorWidget\Foundation\ThirdParty\Illuminate\Contracts\Bus\Dispatcher as BusDispatcherContract;

/**
 * @see \GravityKit\GravityFormsElementorWidget\Foundation\ThirdParty\Illuminate\Contracts\Bus\Dispatcher
 */
class Bus extends Facade
{
    /**
     * Replace the bound instance with a fake.
     *
     * @return void
     */
    public static function fake()
    {
        static::swap(new BusFake);
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return BusDispatcherContract::class;
    }
}
