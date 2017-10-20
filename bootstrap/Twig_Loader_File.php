<?php

/**
 * Twig_Loader_File
 */
class Twig_Loader_File extends Twig_Loader_Filesystem
{
    protected function findTemplate($name, $throw = true)
    {
        if(isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        if(is_file($name)) {
            $this->cache[$name] = $name;
            return $name;
        }

        return parent::findTemplate($name);
    }
}
