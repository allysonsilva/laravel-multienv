<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class DomainController extends BaseController
{
    public function index(Request $request)
    {
        return $request->getHost() . $request->getRequestUri();
    }
}
