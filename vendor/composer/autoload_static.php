<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3bf3cff0046aa7ca5c0086b34e6669b0
{
    public static $files = array (
        'b84f588f8988d4897677a1b3b8edf2b1' => __DIR__ . '/../..' . '/includes/function.php',
    );

    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Training\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Training\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3bf3cff0046aa7ca5c0086b34e6669b0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3bf3cff0046aa7ca5c0086b34e6669b0::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3bf3cff0046aa7ca5c0086b34e6669b0::$classMap;

        }, null, ClassLoader::class);
    }
}
