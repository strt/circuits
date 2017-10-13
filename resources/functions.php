<?php

require_once dirname(__DIR__) . '/bootstrap/app.php';


use App\Http\Controllers\HomeController;

// Setup where to find our theme.
array_map(
    'add_filter',
    ['theme_file_path', 'theme_file_uri', 'parent_theme_file_path', 'parent_theme_file_uri'],
    array_fill(0, 4, 'dirname')
);
