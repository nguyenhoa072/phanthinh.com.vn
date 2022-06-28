<?php

namespace App\Repositories\Home;
 
use App\Repositories\HomeRepository;
use App\Models\Home\TestimonialModel;
 
class TestimonialRepository extends HomeRepository
{
	public function __construct()
	{
		parent::__construct();
		$this->model = new TestimonialModel();
	}

	public function getTestimonials($limit = null)
	{
		$rows = $this->model->select();
		if (!is_null($limit)) {
			$rows->limit($limit);
		}
		return $rows->get();
	}
}