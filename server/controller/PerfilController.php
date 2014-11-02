<?php

class PerfilController
{
	public function __construct()
	{
		$drawing = new PerfilDrawing();

		$drawing->drawPage( 'Que puedo' );
	} // end __construct
} // end IndexController

$page = new PerfilController();

