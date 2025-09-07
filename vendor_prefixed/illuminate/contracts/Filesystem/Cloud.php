<?php
/**
 * @license MIT
 *
 * Modified by gravitykit on 22-November-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace GravityKit\GravityFormsElementorWidget\Foundation\ThirdParty\Illuminate\Contracts\Filesystem;

interface Cloud extends Filesystem
{
    /**
     * Get the URL for the file at the given path.
     *
     * @param  string  $path
     * @return string
     */
    public function url($path);
}
