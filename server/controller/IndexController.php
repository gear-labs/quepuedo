<?php

class IndexController
{
	public function __construct()
	{
		$drawing = new IndexDrawing();

		$drawing->drawPage( 'Que puedo' );
	} // end __construct
} // end IndexController

$page = new IndexController();

