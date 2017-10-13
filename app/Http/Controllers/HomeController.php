<?php

namespace App\Http\Controllers;

class HomeController
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
