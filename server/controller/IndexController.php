<?php
require 'server/base/library/Controller/ControllerAJAX.php';
use Gear\Controller\ControllerAJAX;

require 'server/model/FoodModel.php';

class IndexController extends ControllerAJAX
{
	private $drawing;

	public function __construct()
	{
		$this->drawing = new IndexDrawing();

		if( isset( $_POST[ 'action' ] ) )
			$this->callFunctions( $_POST[ 'action' ] );

		$this->drawing->drawPage( 'Que puedo', array( 'PromoFoods()' ) );

	} // end __construct

	private function callFunctions( $action )
	{
		switch( $action )
		{
			case 'searchFood':
				$parameters = json_decode( $_POST[ 'parameters' ], true );
				$this->callDraw( $this->drawing, 'SearchFoods', array( $parameters, true ) );
				break;
		} // end switch

		exit;
	} // end callFunctions
} // end IndexController

$page = new IndexController();

