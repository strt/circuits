<?php

use Illuminate\Container\Container;

/**
 * This will be moved into library in future.
 *
 **/
function app($abstract = null, $data = []) {
    $container = Container::getInstance();

    if (is_null($abstract)) {
        return $container;
    }

    return empty($data)
        ? $container->make($abstract)
        : $container->makeWith($abstract, $data);
}

function config($name, $default = null) {
    return app('config')->get($name, $default);
}

function view($template, $data = []) {
    $template = str_replace('.', '/', $template);
    $template = app('twig')->load("{$template}.twig");
    return $template->render($data);
}
