<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7f9550b97c7dc91ba3f822c131010a99
{
    public static $prefixLengthsPsr4 = array (
        'a' => 
        array (
            'app\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'PP\\Mpesa\\Auth' => __DIR__ . '/../..' . '/src/mpesa/Auth.php',
        'PP\\Mpesa\\B2B' => __DIR__ . '/../..' . '/src/mpesa/B2B.php',
        'PP\\Mpesa\\B2C' => __DIR__ . '/../..' . '/src/mpesa/B2C.php',
        'PP\\Mpesa\\C2B' => __DIR__ . '/../..' . '/src/mpesa/C2B.php',
        'PP\\Mpesa\\Config' => __DIR__ . '/../..' . '/src/mpesa/Config.php',
        'PP\\Mpesa\\Core' => __DIR__ . '/../..' . '/src/mpesa/Core.php',
        'PP\\Mpesa\\Init' => __DIR__ . '/../..' . '/src/mpesa/Init.php',
        'PP\\Mpesa\\LNMO' => __DIR__ . '/../..' . '/src/mpesa/LNMO.php',
        'PP\\Mpesa\\MpesaTraits' => __DIR__ . '/../..' . '/src/mpesa/MpesaTraits.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7f9550b97c7dc91ba3f822c131010a99::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7f9550b97c7dc91ba3f822c131010a99::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7f9550b97c7dc91ba3f822c131010a99::$classMap;

        }, null, ClassLoader::class);
    }
}
