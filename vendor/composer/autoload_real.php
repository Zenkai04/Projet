<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitfa3fc883a0d88a9df9fe4854c23275ff
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

        spl_autoload_register(array('ComposerAutoloaderInitfa3fc883a0d88a9df9fe4854c23275ff', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitfa3fc883a0d88a9df9fe4854c23275ff', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitfa3fc883a0d88a9df9fe4854c23275ff::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
