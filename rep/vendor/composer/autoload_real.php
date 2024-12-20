<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit070c98e2df1f74b655e7d481ebe3004b
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit070c98e2df1f74b655e7d481ebe3004b', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit070c98e2df1f74b655e7d481ebe3004b', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit070c98e2df1f74b655e7d481ebe3004b::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
