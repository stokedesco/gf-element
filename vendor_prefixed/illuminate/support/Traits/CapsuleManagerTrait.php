<?php
/**
 * @license MIT
 *
 * Modified by gravitykit on 22-November-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace GravityKit\GravityFormsElementorWidget\Foundation\ThirdParty\Illuminate\Support\Traits;

use GravityKit\GravityFormsElementorWidget\Foundation\ThirdParty\Illuminate\Support\Fluent;
use GravityKit\GravityFormsElementorWidget\Foundation\ThirdParty\Illuminate\Contracts\Container\Container;

trait CapsuleManagerTrait
{
    /**
     * The current globally used instance.
     *
     * @var object
     */
    protected static $instance;

    /**
     * The container instance.
     *
     * @var \GravityKit\GravityFormsElementorWidget\Foundation\ThirdParty\Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * Setup the IoC container instance.
     *
     * @param  \GravityKit\GravityFormsElementorWidget\Foundation\ThirdParty\Illuminate\Contracts\Container\Container  $container
     * @return void
     */
    protected function setupContainer(Container $container)
    {
        $this->container = $container;

        if (! $this->container->bound('config')) {
            $this->container->instance('config', new Fluent);
        }
    }

    /**
     * Make this capsule instance available globally.
     *
     * @return void
     */
    public function setAsGlobal()
    {
        static::$instance = $this;
    }

    /**
     * Get the IoC container instance.
     *
     * @return \GravityKit\GravityFormsElementorWidget\Foundation\ThirdParty\Illuminate\Contracts\Container\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Set the IoC container instance.
     *
     * @param  \GravityKit\GravityFormsElementorWidget\Foundation\ThirdParty\Illuminate\Contracts\Container\Container  $container
     * @return void
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }
}
