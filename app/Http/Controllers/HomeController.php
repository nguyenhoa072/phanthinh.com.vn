<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Helpers\General;

class HomeController extends Controller
{
    protected $data;
    protected $home_service;

    public function __construct()
    {
        $this->data = [];
    }
}


