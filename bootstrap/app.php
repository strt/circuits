<?php

require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/vendor/autoload.php';
require_once "RepositoryLoader.php";
require_once "twig.php";
require_once "helpers.php";

use Illuminate\Container\Container;
use Illuminate\Config\Repository as Config;

// Setup container
$app = Container::getInstance();

// Setup config
$app->singleton('config', function ($app) {
    return new Config([]);
});

// Setup twig
$app->singleton('twig', function ($app) {
    $loader = new Twig_Loader_file(dirname(__DIR__) . '/resources/views');
    return new Twig_Environment($loader, [
        // 'cache' => __DIR__ . '/cache'
    ]);
});

// Load templater
// Refactor later
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

// Refactor into own folder later on
// Bootstrap config
RepositoryLoader::load();

// Catch all template hierachy types and redirect it into our filter.
collect([
    'index', '404', 'archive', 'author', 'category', 'tag', 'taxonomy', 'date', 'home',
    'frontpage', 'page', 'paged', 'search', 'single', 'singular', 'attachment'
])->map(function ($type) {
    add_filter("{$type}_template_hierarchy", __NAMESPACE__.'\\filter_templates');
});

add_filter('template_include', function ($template) {
    return get_stylesheet_directory() . '/index.php';
    // Legacy
    // Keep if needed for reference soon.
    // dump($template);
    // $data = collect(get_body_class())->reduce(function ($data, $class) use ($template) {
    //     return apply_filters("betest/template/{$class}/data", $data, $class);
    // }, []);
    // if ($template) {
    //     echo template($template, $data);
    //     return get_stylesheet_directory().'/index.php';
    // }
}, PHP_INT_MAX);

function filter_templates($templates) {
    // Default action
    $action = function () {
        echo app()->call("\\App\\Http\\Controllers\\HomeController@index");
    };

    // Loop through all templates and check if a controller exists.
    foreach ($templates as $template) {
        // Get the controller part of it.
        $template = explode('@', $template);
        $controller = array_shift($template);
        $controller = "\\App\\Http\\Controllers\\{$controller}";

        // Check if controller exists
        if (class_exists($controller)) {
            $method = array_pop($template);
            $action = function () use ($controller, $method) {
                echo app()->call("{$controller}@{$method}");
            };

            // More than one controller would be stupid.
            break;
        }
    }

    $action();

    // Ugly fix for now
    return 'index.php';

    // Legacy keep for reference
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

// function template($file, $data = [])
// {
//     // dump($file);
//     // $class = "App\\Http\\Controllers\\" . $file;
//     // if (class_exists($class)) {
//     //     $class = new $class;
//     //     dump($class);
//     // }
//     if (remove_action('wp_head', 'wp_enqueue_scripts', 1)) {
//         wp_enqueue_scripts();
//     }
//     $template = app('twig')->load($file);
//     return $template->render($data);
// }

app('templater')->add('Test Template', 'HomeController@index');
app('templater')->add('Home Template', 'HomeController@home');
app('templater')->add('Lul Template', 'HomeController@luld');

// Allow our controllers to be chosen in the edit view.
add_filter('theme_page_templates', function ($template) {
    return app('templater')->get();
});

// Allow our controllers to be chosen in ACF locations.
add_filter('acf/location/rule_values/post_template', function ($choices) {
    // Redo later
    return array_merge(["default" => "Standardmall"], app('templater')->get());
}, 1, 1);
