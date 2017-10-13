<?php

use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class RepositoryLoader
{
    public static function load($files = [])
    {
        $configPath = realpath(dirname(__DIR__) . '/config');

        foreach (Finder::create()->files()->name('*.php')->in($configPath) as $file) {
            $directory = static::getNestedDirectory($configPath, $file);
            $files[$directory . basename($file->getRealPath(), '.php')] = $file->getRealPath();
        }

        $config = Container::getInstance()->make('config');
        foreach($files as $key => $path) {
            $config->set($key, require $path);
        }
    }

    public static function getNestedDirectory($configPath, $file)
    {
        $directory = $file->getRealPath();

        if ($nested = trim(str_replace($configPath, '', $directory), DIRECTORY_SEPARATOR)) {
            $nested = str_replace(DIRECTORY_SEPARATOR, '.', $nested) . '.';
        }

        return $nested;
    }
}
