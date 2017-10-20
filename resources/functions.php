<?php

require_once dirname(__DIR__) . '/bootstrap/app.php';

/**
 * Set hook on wordpress functions and modifies the path returned to current dir.
 * @author Alexander Karlsson <alexander.karlsson@strateg.se>
 * @link https://developer.wordpress.org/reference/hooks/theme_file_path/ theme_file_path hook
 * @link https://developer.wordpress.org/reference/hooks/theme_file_uri/ theme_file_uri hook
 * @link https://developer.wordpress.org/reference/hooks/parent_theme_file_path/ parent_theme_file_path hook
 * @link https://developer.wordpress.org/reference/hooks/parent_theme_file_uri/ parent_theme_file_uri hook
 * @return array
 */
array_map(
    'add_filter',
    ['theme_file_path', 'theme_file_uri', 'parent_theme_file_path', 'parent_theme_file_uri'],
    array_fill(0, 4, 'dirname')
);
