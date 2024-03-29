<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4f4c40c346bb071f6632941556a0b6d6
{
    public static $files = array (
        'f3f72e8f45cee0438bb10bcff0dc03e7' => __DIR__ . '/../..' . '/config/Tgm/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'ES\\' => 3,
        ),
        'D' => 
        array (
            'Dotenv\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ES\\' => 
        array (
            0 => __DIR__ . '/../..' . '/config',
        ),
        'Dotenv\\' => 
        array (
            0 => __DIR__ . '/..' . '/vlucas/phpdotenv/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4f4c40c346bb071f6632941556a0b6d6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4f4c40c346bb071f6632941556a0b6d6::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
