<?php

class RegistroController
{
	public function __construct()
	{
		$drawing = new RegistroDrawing();

		$drawing->drawPage( 'Que puedo' );
	} // end __construct
} // end IndexController

$page = new RegistroController();

