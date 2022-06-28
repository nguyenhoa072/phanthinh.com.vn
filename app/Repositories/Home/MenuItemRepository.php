<?php

namespace App\Repositories\Home;
 
use App\Repositories\HomeRepository;
use App\Models\Home\MenuItemModel;
 
class MenuItemRepository extends HomeRepository
{
	public function __construct()
	{
		parent::__construct();
		$this->model = new MenuItemModel();
	}

	public function getMenu()
	{
		return $this->model->get();
	}
}