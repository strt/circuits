<?php

use Illuminate\Container\Container;

/**
 * This will be moved into library in future.
 *
 **/
function app($abstract, $data = []) {
    $container = Container::getInstance();

    return empty($data)
        ? $container->make($abstract)
        : $container->makeWith($abstract, $data);
}

function config($name, $default = null) {
    return app('config')->get($name, $default);
}

function view($template, $data = []) {
    $template = app('twig')->load($template);
    return $template->render($data);
}
