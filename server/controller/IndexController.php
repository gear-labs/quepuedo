<?php

require 'server/model/FoodModel.php';

class IndexController
{
	public function __construct()
	{
		$drawing = new IndexDrawing();

		$drawing->drawPage( 'Que puedo', array( 'PromoFoods()' ) );
	} // end __construct
} // end IndexController

$page = new IndexController();

