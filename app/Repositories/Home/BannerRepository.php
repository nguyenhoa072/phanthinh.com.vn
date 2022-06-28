<?php

namespace App\Repositories\Home;
 
use App\Repositories\HomeRepository;
use App\Models\Home\BannerModel;
 
class BannerRepository extends HomeRepository
{
	public function __construct()
	{
		parent::__construct();
		$this->model = new BannerModel();
	}

	public function getBanners($limit = null)
	{
		$rows = $this->model->select();
		if (!is_null($limit)) {
			$rows->limit($limit);
		}
		return $rows->get();
	}
}