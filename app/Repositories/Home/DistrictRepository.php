<?php

namespace App\Repositories\Home;
 
use App\Repositories\HomeRepository;
use App\Models\Home\DistrictModel;
 
class DistrictRepository extends HomeRepository
{
	public function __construct()
	{
		parent::__construct();
		$this->model = new DistrictModel();
	}

	public function getDistricts($filter_provice_id = null)
	{
		$rows = $this->model->select();
		if (!is_null($filter_provice_id)) {
			$rows->where('province_id', $filter_provice_id);
		}
		return $rows->get();
	}
}