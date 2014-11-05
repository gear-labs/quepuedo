<?php

namespace Gear\Controller;

class ControllerAJAX
{	
	private $method;
	
	//Recibe un arreglo en donde se pasan los argumentos de la funcion que retorna los datos actualizados
	//El orden de los valores debe ser igual al orden de los argumentos
	public function callDraw( &$objDrawing, $method, $parameters = array() )
	{
		$returns = array();//almacena los valores devueltos en el update de datos
		
		$returns = call_user_func_array( array( $objDrawing, 'draw' . $method ), $parameters );

		echo json_encode( $returns );
	}//end callDraw

}//end ControllerAJAX