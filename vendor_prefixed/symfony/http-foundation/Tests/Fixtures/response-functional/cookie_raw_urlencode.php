<?php
/**
 * @license MIT
 *
 * Modified by gravitykit on 22-November-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

use GravityKit\GravityFormsElementorWidget\Symfony\Component\HttpFoundation\Cookie;

$r = require __DIR__.'/common.inc';

$str = '?*():@&+$/%#[]';

$r->headers->setCookie(new Cookie($str, $str, 0, '/', null, false, false, true));
$r->sendHeaders();

setrawcookie($str, $str, 0, '/', null, false, false);
