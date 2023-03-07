<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5637b81e45c93e3bf0b7bc08cb84403f
{
    public static $classMap = array (
        'Smartcrawl_Vendor\\SyllableTest' => __DIR__ . '/../..' . '/vendor_prefixed/syllable/tests/SyllableTest.php',
        'Smartcrawl_Vendor\\Vanderlee\\Syllable\\Cache\\Cache' => __DIR__ . '/../..' . '/vendor_prefixed/syllable/src/Cache/Cache.php',
        'Smartcrawl_Vendor\\Vanderlee\\Syllable\\Cache\\File' => __DIR__ . '/../..' . '/vendor_prefixed/syllable/src/Cache/File.php',
        'Smartcrawl_Vendor\\Vanderlee\\Syllable\\Cache\\Json' => __DIR__ . '/../..' . '/vendor_prefixed/syllable/src/Cache/Json.php',
        'Smartcrawl_Vendor\\Vanderlee\\Syllable\\Cache\\Serialized' => __DIR__ . '/../..' . '/vendor_prefixed/syllable/src/Cache/Serialized.php',
        'Smartcrawl_Vendor\\Vanderlee\\Syllable\\Hyphen\\Dash' => __DIR__ . '/../..' . '/vendor_prefixed/syllable/src/Hyphen/Dash.php',
        'Smartcrawl_Vendor\\Vanderlee\\Syllable\\Hyphen\\Entity' => __DIR__ . '/../..' . '/vendor_prefixed/syllable/src/Hyphen/Entity.php',
        'Smartcrawl_Vendor\\Vanderlee\\Syllable\\Hyphen\\Hyphen' => __DIR__ . '/../..' . '/vendor_prefixed/syllable/src/Hyphen/Hyphen.php',
        'Smartcrawl_Vendor\\Vanderlee\\Syllable\\Hyphen\\Soft' => __DIR__ . '/../..' . '/vendor_prefixed/syllable/src/Hyphen/Soft.php',
        'Smartcrawl_Vendor\\Vanderlee\\Syllable\\Hyphen\\Text' => __DIR__ . '/../..' . '/vendor_prefixed/syllable/src/Hyphen/Text.php',
        'Smartcrawl_Vendor\\Vanderlee\\Syllable\\Hyphen\\ZeroWidthSpace' => __DIR__ . '/../..' . '/vendor_prefixed/syllable/src/Hyphen/ZeroWidthSpace.php',
        'Smartcrawl_Vendor\\Vanderlee\\Syllable\\Source\\File' => __DIR__ . '/../..' . '/vendor_prefixed/syllable/src/Source/File.php',
        'Smartcrawl_Vendor\\Vanderlee\\Syllable\\Source\\Source' => __DIR__ . '/../..' . '/vendor_prefixed/syllable/src/Source/Source.php',
        'Smartcrawl_Vendor\\Vanderlee\\Syllable\\Syllable' => __DIR__ . '/../..' . '/vendor_prefixed/syllable/src/Syllable.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit5637b81e45c93e3bf0b7bc08cb84403f::$classMap;

        }, null, ClassLoader::class);
    }
}
