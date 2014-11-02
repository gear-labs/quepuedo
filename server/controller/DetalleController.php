<?php

class DetalleController
{
	public function __construct()
	{
		$drawing = new DetalleDrawing();

		$drawing->drawPage( 'Que puedo' );
	} // end __construct
} // end IndexController

$page = new DetalleController();

