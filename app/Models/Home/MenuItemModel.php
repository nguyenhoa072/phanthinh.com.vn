<?php

namespace App\Models\Home;

use App\Models\HomeModel;

class MenuItemModel extends HomeModel
{
	protected $table = 'menu_items';

    public function __construct()
    {
    	parent::__construct();
    }

}