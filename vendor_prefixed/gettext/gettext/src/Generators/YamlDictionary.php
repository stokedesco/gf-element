<?php
/**
 * @license MIT
 *
 * Modified by gravitykit on 22-November-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace GravityKit\GravityFormsElementorWidget\Foundation\ThirdParty\Gettext\Generators;

use GravityKit\GravityFormsElementorWidget\Foundation\ThirdParty\Gettext\Translations;
use GravityKit\GravityFormsElementorWidget\Foundation\ThirdParty\Gettext\Utils\DictionaryTrait;
use Symfony\Component\Yaml\Yaml as YamlDumper;

class YamlDictionary extends Generator implements GeneratorInterface
{
    use DictionaryTrait;

    public static $options = [
        'includeHeaders' => false,
        'indent' => 2,
        'inline' => 3,
    ];

    /**
     * {@inheritdoc}
     */
    public static function toString(Translations $translations, array $options = [])
    {
        $options += static::$options;

        return YamlDumper::dump(
            static::toArray($translations, $options['includeHeaders']),
            $options['inline'],
            $options['indent']
        );
    }
}
