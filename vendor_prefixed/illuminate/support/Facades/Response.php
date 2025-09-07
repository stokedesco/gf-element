<?php
/**
 * @license MIT
 *
 * Modified by gravitykit on 22-November-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace GravityKit\GravityFormsElementorWidget\Foundation\ThirdParty\Illuminate\Support\Facades;

use GravityKit\GravityFormsElementorWidget\Foundation\ThirdParty\Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

/**
 * @see \GravityKit\GravityFormsElementorWidget\Foundation\ThirdParty\Illuminate\Contracts\Routing\ResponseFactory
 */
class Response extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ResponseFactoryContract::class;
    }
}
