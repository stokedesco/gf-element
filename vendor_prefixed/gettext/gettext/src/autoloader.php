<?php
/**
 * @license MIT
 *
 * Modified by gravitykit on 22-November-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

spl_autoload_register(function ($class) {
    if (strpos($class, 'GravityKit\\GravityFormsElementorWidget\\Foundation\\ThirdParty\\Gettext\\') !== 0) {
        return;
    }

    $file = __DIR__.str_replace('\\', DIRECTORY_SEPARATOR, substr($class, strlen('GravityKit\GravityFormsElementorWidget\Foundation\ThirdParty\Gettext'))).'.php';

    if (is_file($file)) {
        require_once $file;
    }
});
