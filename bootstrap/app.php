<?php

require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/vendor/autoload.php';
require_once "RepositoryLoader.php";
require_once "twig.php";

use Illuminate\Container\Container;
use Illuminate\Config\Repository as Config;

$app = Container::getInstance();
$app->singleton('config', function ($app) {
    return new Config([]);
});
$app->singleton('twig', function ($app) {
    $loader = new Twig_loader_File(dirname(__DIR__) . '/resources/views');
    return new Twig_Environment($loader, [
        // 'cache' => __DIR__ . '/cache'
    ]);
});

function app($abstract, $data = []) {
    $container = Container::getInstance();

    return empty($data)
        ? $container->make($abstract)
        : $container->makeWith($abstract, $data);
}

// Refactor into its own function later on.
RepositoryLoader::load();

// Setup filter templates
collect([
    'index', '404', 'archive', 'author', 'category', 'tag', 'taxonomy', 'date', 'home',
    'frontpage', 'page', 'paged', 'search', 'single', 'singular', 'attachment'
])->map(function ($type) {
    add_filter("{$type}_template_hierarchy", __NAMESPACE__.'\\filter_templates');
    // add_filter("{$type}_template", __NAMESPACE__ . '\\second_test', 1, 3);
    // add_filter("{$type}_template", __NAMESPACE__ . '\\filter_templates');
});

add_filter('template_include', function ($template) {
    // dump($template);
    // $data = collect(get_body_class())->reduce(function ($data, $class) use ($template) {
    //     return apply_filters("betest/template/{$class}/data", $data, $class);
    // }, []);
    // if ($template) {
    //     echo template($template, $data);
    //     return get_stylesheet_directory().'/index.php';
    // }
    return $template;
}, PHP_INT_MAX);

// Loop through all controllers in future, just PoC here.
add_filter('betest/template/home/data', function ($data, $class) {
    $class = 'App\\Http\\Controllers\\' . ucwords($class) . 'Controller';
    if (class_exists($class)) {
        $class = new $class;
        return $class->compile();
    }
}, 1, 2);

function filter_templates($templates) {
    foreach ($templates as $template) {
        $template = explode('@', $template);
        $controller = array_shift($template);
        $controller = "\\App\\Http\\Controllers\\{$controller}";
        if (class_exists($controller)) {
            $method = array_pop($template);
            app($controller)->{$method}('hej');
            break;
        }
    }

    return [];
    // foreach ($templates as $template) {
    //     $template = explode('@', $template);
    //     $template = reset($template);
    //     if (class_exists("\\App\\Http\\Controllers\\{$template}")) {
    //         // return [$template];
    //     }
    // }
    // $template = [
    //     array_shift($template),
    //     'default'
    // ];

    // return $template;
    // dump($templates);
    // $paths = [
    //     'views',
    //     'resources/views'
    // ];
    // $paths_pattern = "#^(" . implode('|', $paths) . ")/#";

    // return collect($templates)
    // ->map(function ($template) use ($paths_pattern) {
    //     /** Remove .blade.php/.blade/.php from template names */
    //     $template = preg_replace('#\.(blade\.?)?(php)?$#', '', ltrim($template));
    //     /** Remove partial $paths from the beginning of template names */
    //     if (strpos($template, '/')) {
    //         $template = preg_replace($paths_pattern, '', $template);
    //     }
    //     return $template;
    // })
    // ->flatMap(function ($template) use ($paths) {
    //     return collect($paths)
    //         ->flatMap(function ($path) use ($template) {
    //             return [
    //                 "{$path}/{$template}.twig",
    //                 "{$path}/{$template}.twig",
    //                 "{$template}.twig",
    //                 "{$template}.twig",
    //             ];
    //         });
    // })
    // ->filter()
    // ->unique()
    // ->all();
}

function second_test($template, $type, $templates) {
    return array_shift($templates);
}

function template($file, $data = [])
{
    // dump($file);
    // $class = "App\\Http\\Controllers\\" . $file;
    // if (class_exists($class)) {
    //     $class = new $class;
    //     dump($class);
    // }
    if (remove_action('wp_head', 'wp_enqueue_scripts', 1)) {
        wp_enqueue_scripts();
    }
    $template = app('twig')->load($file);
    return $template->render($data);
}

function config($name, $default = null) {
    return app('config')->get($name, $default);
}

class TestTemplater
{
    protected $templates;

    public function add($name, $action)
    {
        $this->templates[$action] = $name;
    }

    public function get()
    {
        return $this->templates;
    }
}

$app->singleton('templater', function($app) {
    return new TestTemplater;
});

app('templater')->add('Test Template', 'HomeController@index');
app('templater')->add('Home Template', 'HomeController@home');
app('templater')->add('Lul Template', 'HomeController@luld');

add_filter('theme_page_templates', function ($template) {
    return app('templater')->get();
});

add_filter('acf/location/rule_values/post_template', function ($choices) {
    return array_merge(["default" => "Standardmall"], app('templater')->get());
}, 1, 1);
