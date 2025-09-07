<?php
/**
 * @license MIT
 *
 * Modified by gravitykit on 22-November-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace GravityKit\GravityFormsElementorWidget\Foundation\ThirdParty\Illuminate\Contracts\Filesystem;

interface Factory
{
    /**
     * Get a filesystem implementation.
     *
     * @param  string  $name
     * @return \GravityKit\GravityFormsElementorWidget\Foundation\ThirdParty\Illuminate\Contracts\Filesystem\Filesystem
     */
    public function disk($name = null);
}
