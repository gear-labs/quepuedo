<?php
use Gear\DB\GMongo;

class FoodModel
{
	public function getFoods()
	{
		$fields = array(
			'restaurant' => 1,
			'menu' => 1,
			'_id' => 0,
		);

		return GMongo::getRegisters( 'foods', $fields );

	} // end getFood
} // end FoodModel