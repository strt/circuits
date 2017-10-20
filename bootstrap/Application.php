<?php

use Illuminate\Container\Container;

class Application extends Container
{
    protected $hasBeenBootstrapped;

    public function __construct($basePath = null)
    {
        if ($basePath) {
            $this->setBasePath($basePath);
        }

        $this->registerBaseBindings();
    }

    protected function registerBaseBindings()
    {
        static::setInstance($this);

        $this->instance('app', $this);

        $this->instance(Container::class, $this);

        foreach([
            'app'       => [Application::class, \Illuminate\Contracts\Container\Container::class, \Psr\Container\ContainerInterface::class],
            'config'    => [\Illuminate\Config\Repository::class, \Illuminate\Contracts\Config\Repository::class],
        ] as $key => $aliases) {
            foreach ($aliases as $alias) {
                $this->alias($key, $alias);
            }
        }
    }

    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '\/');

        return $this;
    }

    public function bootstrapWith(array $bootstrappers)
    {
        $this->hasBeenBootstrapped = true;

        foreach ($bootstrappers as $bootstrapper) {
            $this->make($bootstrapper)->bootstrap($this);
        }
    }

    public function hasBeenBootstrapped()
    {
        return $this->hasBeenBootstrapped;
    }

    public function configPath($path = '')
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'config'.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    public function shouldSkipMiddleware()
    {
        return true;
    }
}
