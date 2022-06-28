<?php

namespace App\Repositories;
 
class HomeRepository
{
	protected $hotel_table 				= 'hotels';
	protected $room_table  				= 'rooms';
	protected $banner_table 			= 'banners';
	protected $district_table 			= 'districts';
	protected $province_table 			= 'provinces';
	protected $menu_item_table 			= 'menu_items';
	protected $room_statistic_table 	= 'room_statistics';
	protected $booking_item_table 	    = 'booking_items';
	protected $room_price_table 	    = 'room_prices';
	protected $room_type_table 	        = 'room_types';
	protected $room_category_table 	    = 'room_categories';
	protected $model;

	public function __construct()
	{
		
	}
}