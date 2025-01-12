<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite6989670f2e8c5c6fe8e5d175fb5f33c
{
    public static $files = array (
        '8de2ebbf0ac7a66e03a882d006b27458' => __DIR__ . '/../..' . '/includes/helpers.php',
        'c9a7e7fa37e456021511338b6985e651' => __DIR__ . '/../..' . '/includes/setup.php',
    );

    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Composer\\Installers\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Composer\\Installers\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite6989670f2e8c5c6fe8e5d175fb5f33c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite6989670f2e8c5c6fe8e5d175fb5f33c::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
