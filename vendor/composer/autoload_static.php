<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitada6e9dc3e17c48d2a219a137bacfffb
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twig\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twig\\' => 
        array (
            0 => __DIR__ . '/..' . '/twig/twig/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'T' => 
        array (
            'Twig_' => 
            array (
                0 => __DIR__ . '/..' . '/twig/twig/lib',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitada6e9dc3e17c48d2a219a137bacfffb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitada6e9dc3e17c48d2a219a137bacfffb::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitada6e9dc3e17c48d2a219a137bacfffb::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
