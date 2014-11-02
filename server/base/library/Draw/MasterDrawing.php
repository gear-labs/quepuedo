<?php

namespace Gear\Draw;

class MasterDrawing
{
	protected $className;

	protected $list;
	protected $template;

	protected function __construct()
	{
		$this->className = str_replace( 'Drawing', '', get_class( $this ) );
	} // end __construct

	protected function translateConst()
	{
		global $drawer; // Obtiene el drawer declarado en process.php
		$this->template = $drawer->draw( $this->list, $this->template );
	} // end translateConst

	/**
	 * Establece el template de un nivel de
	 * usuario determinado
	 *
	 * Nombre del archivo html
	 * @var name
	 *
	 * Permite saber si quiere que se modifique el valor
	 * de $this->template, si es false, se modifican,
	 * sino se asigna el resultado obtenido en la variable
	 * pasada por referencia
	 * @var templateVar
	 */
	protected function setTemplate( $name, &$templateVar = false )
	{
		// Separa las partes del nombre de la clase en caso de tener
		// mas de una sola mayuscula en su nombre
		$pieces = preg_split( '/(?=[A-Z])/', lcfirst( $this->className ) );

		// Si hubo mayusculas
		if( isset( $pieces ) )
		{
			$this->className = lcfirst( $pieces[ 0 ] );		

			for( $i = 1; $i < sizeof( $pieces ); $i++ )
				$this->className .= '-' . lcfirst( $pieces[ $i ] );

		} // end if

		//Establece el directorio de las listas de una vista			
		$directory = 'client/html/master/' . lcfirst( $this->className ) .'/';

		// Obtiene el template a procesar
		if( $templateVar === false )
			$this->template = file_get_contents( $directory . $name . '.html' );
		else
			$templateVar = file_get_contents( $directory . $name . '.html' );

	} // end setList
}//end MasterView