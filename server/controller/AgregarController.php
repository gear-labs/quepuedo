<?php

class AgregarController
{
	public function __construct()
	{
		$drawing = new AgregarDrawing();

		$drawing->drawPage( 'Que puedo' );
	} // end __construct
} // end IndexController

$page = new AgregarController();

