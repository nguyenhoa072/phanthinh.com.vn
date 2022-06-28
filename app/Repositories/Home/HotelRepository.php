<?php

namespace App\Repositories\Home;

use Illuminate\Support\Facades\DB;
use App\Repositories\HomeRepository;
use App\Models\Home\HotelModel;
 
class HotelRepository extends HomeRepository
{
	public function __construct()
	{
		parent::__construct();
		$this->model = new HotelModel();
	}

	public function getHotels($params = [], $limit = null)
	{
		$rows = $this->model->select();
		if (isset($params['province_id']) && !empty($params['province_id'])) {
			$rows->where('province_id', $params['province_id']);
		}
		if (isset($params['district_id']) && !empty($params['district_id'])) {
			$rows->where('district_id', $params['district_id']);
		}
		if (!is_null($limit)) {
			$rows->limit($limit);
		}
		return $rows->get();
	}

	public function getTopDestinations($district_id = null)
	{
		$params = [];
		$wheres = [
			'`hotel`.`is_deleted` = 0',
			'`hotel`.`is_actived` = 1'
		];
		if (!is_null($district_id)) {
			$wheres[] = '`hotel`.`district_id` = :district_id';
		}
		$params['district_id'] = $district_id;
		$day = date('d');

		$wheres = implode(' AND ', $wheres);
		$sql = <<<SQL
			SELECT 
				*,
				COUNT(`district_id`) as total,
				IFNULL(min_price, 0) AS final_min_price
			FROM (
				SELECT 
					`district`.`id` AS `district_id`,
					`district`.`name` AS `district_name`,
					`district`.`type` AS `district_type`,
					`district`.`image` AS `district_image`,
					`province`.`name` AS `province_name`,
					`province`.`name` AS `province_type`,
					(
						SELECT 
							IFNULL(MIN(`room_price`.`price`), 0)
						FROM $this->room_table AS `room`
						INNER JOIN $this->room_price_table AS `room_price`
							ON `room_price`.`room_id` = `room`.`id`
						WHERE 
							`room`.`is_deleted` = 0
							AND `room`.`is_actived` = 1
							AND `room`.`is_approved` = 1
							AND `room`.`hotel_id` = `hotel`.`id`
							AND `room_price`.`day_in_month` = $day
						GROUP BY `hotel`.`id`
					) AS min_price
				FROM $this->hotel_table AS `hotel`
				JOIN $this->district_table AS `district`
					ON `district`.`id` = `hotel`.`district_id`
				JOIN $this->province_table AS `province`
					ON `district`.`province_id` = `province`.`id`
				WHERE 
					$wheres
				GROUP BY `hotel`.`id`
				ORDER BY `min_price`
			) AS `district`
			GROUP BY `district_id`
SQL;
		return collect(DB::select($sql, $params));
	}
}