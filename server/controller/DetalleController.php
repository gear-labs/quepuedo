<?php
require 'server/model/FoodModel.php';

class DetalleController
{
	private $myFood;

	public function __construct()
	{
		$drawing = new DetalleDrawing();
		$this->myFood = new FoodModel();

		$data = $this->getData();

		$drawing->drawPage( 'Que puedo', array(), $data );
	} // end __construct

	private function getData()
	{
		$food = $this->myFood->getFoodInfo( $_GET[ 'food' ] );

		return $data = array(
			'Food' => $food[ 'menu' ][ 'name' ],
			'Info' => $food[ 'info' ],
			'Price' => number_format( $food[ 'menu' ][ 'price' ], 0, '', '.' ),
			'Restaurant' => $food[ 'restaurant' ][ 'restaurant' ]
		);
	} // end getData
} // end IndexController

$page = new DetalleController();

