<?php

use Illuminate\Http\Request as BaseRequest;

class Request extends BaseRequest
{
    public function capture()
    {
        $request = parent::capture();
    }
}
