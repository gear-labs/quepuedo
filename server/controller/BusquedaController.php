<?php

class BusquedaController
{
	public function __construct()
	{
		$drawing = new BusquedaDrawing();

		$drawing->drawPage( 'Que puedo' );
	} // end __construct
} // end IndexController

$page = new BusquedaController();

