<?php

namespace App\Repositories\Home;

use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\HomeRepository;
use App\Models\Home\RoomModel;
 
class RoomRepository extends HomeRepository
{
	private $columns;
	public function __construct()
	{
		parent::__construct();
		$this->model   = new RoomModel();
		$this->columns = $this->getColumns();
	}

	private function getColumns()
	{
		return [
			'hotel.name',
			'room.image',
			'room_type.name AS room_type',
			'room_price.price',
			'room_price.discount_price',
			'room_price.discount_percent',
			'district.id AS district_ide',
			'district.name AS district_name',
			'district.type AS district_type',
			'statistic.reviews_total',
			'statistic.score_avg'
			];
	}

	private function getTotalFreeRoomSql($params)
	{
		$start_date = date('Y-m-d');
		$end_date   = date("Y-m-d", strtotime("+1 day"));

		if (isset($params['start_date']) && !empty($params['start_date'])) {
 			$start_date = $params['start_date'];
		}
		if (isset($params['end_date']) && !empty($params['end_date'])) {
			$end_date = $params['end_date'];
		}

		$total_free_room_sql = <<<SQL
			(SELECT
				(r.quantity - SUM(booking_item.quantity))
			FROM
				$this->room_table AS r
			LEFT JOIN $this->booking_item_table AS booking_item 
				ON booking_item.room_id = r.id
			WHERE r.id = room.id AND 
			start_date >= '$start_date'
			AND end_date <= '$end_date'
				GROUP BY r.id
			) AS total_free_room
SQL;
		return $total_free_room_sql;
	}

	private function getPrimaryClause($params)
	{
		$day = date('d');
		$stmt = $this->model->select(
			$this->columns
		)
		->from($this->room_table ." AS room")
		->join(
			$this->hotel_table ." AS hotel", 
			'hotel.id',
			'=',
			'room.hotel_id'
		)->join(
			$this->room_price_table ." AS room_price", 
			'room_price.room_id',
			'=',
			'room.id'
		)->join(
			$this->room_type_table ." AS room_type", 
			'room_type.id',
			'=',
			'room.type_id'
		)->join(
			$this->district_table ." AS district", 
			'district.id',
			'=',
			'hotel.district_id'
		)->leftJoin(
			$this->room_statistic_table ." AS statistic", 
			'statistic.room_id',
			'=',
			'room.id'
		)
		->where('room.is_actived', 1)
		->where('room.is_deleted', 0)
		->where('room.is_approved', 1)
		->where('hotel.is_actived', 1)
		->where('hotel.is_deleted', 0)
		->where('room_price.day_in_month', $day);

		if (isset($params['province_id']) && !empty($params['province_id'])) {
			$stmt->where('hotel.province_id', $params['province_id']);
		}
		if (isset($params['district_id']) && !empty($params['district_id'])) {
			$stmt->where('hotel.district_id', $params['district_id']);
		}
		return $stmt;
	}

	public function getRooms($params = [], $limit = HOME_ROOM_LIMIT)
	{
		$rows = $this->getPrimaryClause($params);

		if (isset($params['is_favorite']) && !empty($params['is_favorite'])) {
 			$rows->orderBy('statistic.score_avg', 'DESC');
		}

		if ($limit) {
			$rows->limit($limit);
		}
		return $rows->get();
	}

	public function filterRooms($params = [], $limit = HOME_ROOM_LIMIT)
	{
		$sql = $this->getTotalFreeRoomSql($params);
		$this->columns[] = DB::raw($sql);
		$rows = $this->getPrimaryClause($params);

		if (isset($params['exclude_room_id'])) {
			$rows->where('room.id', '<>' , $params['exclude_room_id']);
		}
		return $rows->paginate($limit);
	}

	public function searchRooms($params, $limit = HOME_ROOM_LIMIT)
	{
		$sql = $this->getTotalFreeRoomSql($params);
		$this->columns[] = DB::raw($sql);
		$rows = $this->getPrimaryClause($params);
		return $rows->paginate($limit);
	}

	public function getDetail($room_id)
	{
		$row = $this->model->select();
		$row->from($this->room_table ." AS room");
		$row->join(
			$this->hotel_table ." AS hotel", 
			'hotel.id',
			'=',
			'room.hotel_id'
		);
		$row = $this->getPrimaryWhereClause($row);
		$row->where('room.id', $room_id);
		return $row->first();
	}
}