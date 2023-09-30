<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3a46a88fdfe78b116c09051c9430fe90
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Michielvaneerd\\CountryInfo\\' => 27,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Michielvaneerd\\CountryInfo\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3a46a88fdfe78b116c09051c9430fe90::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3a46a88fdfe78b116c09051c9430fe90::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3a46a88fdfe78b116c09051c9430fe90::$classMap;

        }, null, ClassLoader::class);
    }
}
