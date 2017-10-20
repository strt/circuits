<?php

use Illuminate\Http\Request as BaseRequest;

class Request extends BaseRequest
{
    public function capture($templates)
    {
        $request = parent::capture();

        return $request->
    }
}
