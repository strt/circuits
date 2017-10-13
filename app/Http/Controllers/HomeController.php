<?php

namespace App\Http\Controllers;

class HomeController
{
    public function __construct() {
    }
    /** Move this later */
    public function compile()
    {
        $methods = get_class_methods($this);
        $data = [];

        foreach ($methods as $method) {
            if (! in_array($method, ['__construct', 'compile'])) {
                $data[$method] = $this->{$method}();
            }
        }

        return $data;
    }

    public function home() {
        return view('test.twig');
    }

    public function test()
    {
        return 'hej';
    }

    public function korv()
    {
        return 'falu';
    }
}
