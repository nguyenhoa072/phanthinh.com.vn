<?php

namespace App\Models\Home;

use App\Models\HomeModel;

class BannerModel extends HomeModel
{
	protected $table = 'banners';

    public function __construct()
    {
    	parent::__construct();
    }

}