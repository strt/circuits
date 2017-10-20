<?php

namespace App\Http\Controllers;

class ExampleController
{
    public function __construct(\App\Helpers\Test $test)
    {
        // test
    }

    public function index(\App\Helpers\Test $test)
    {
        return view('index');
    }
}
