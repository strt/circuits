<?php

/**
 * Temporary code for using the twig templating system.
 */
class TwigLoaderFile extends Twig_Loader_Filesystem
{
    protected function findTemplate($name, $throw = true)
    {
        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        if (is_file($name)) {
            $this->cache[$name] = $name;
            return $name;
        }

        return parent::findTemplate($name);
    }
}
