<?php

namespace App\Repositories\Home;
 
use App\Repositories\HomeRepository;
use App\Models\Home\ClientModel;
 
class ClientRepository extends HomeRepository
{
	public function __construct()
	{
		parent::__construct();
		$this->model = new ClientModel();
	}

	public function getClients()
	{
		$rows = $this->model->select();
		return $rows->get();
	}
}