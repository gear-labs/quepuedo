<?php

namespace Gear\Draw;

use Gear\Draw\MasterDrawing;

class Drawing extends MasterDrawing
{
	protected $server; // almacena la url absoluta en donde trabaja el programador		
	protected $template; // template de la lista con el que trabaja el codigo cliente en un momento dado
	protected $list; // lista de los words y su correspondiente traduccion

	protected $principalList = array(); // Almacena los distintos fragmentos html de listados


	public function __construct()
	{
		parent::__construct();
		//Establece la raiz de trabajo del programador
		global $server;
		if( isset( $server ) )
			$this->server = $server . 'server/';
		else
			$this->server = '/server/';

	}//end __construct


	//***********************************************************************************

	/**
	 * Establece el template de una lista
	 * Genera el uri de manera automática
	 * gracias al nombre de la clase desde el
	 * cual fue llamado
	 *
	 * Nombre del archivo html
	 * @var name
	 */
	protected function setList( $name )
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
		$directory = 'client/html/app/' . lcfirst( $this->className ) .'/lists/';
		// Obtiene el template a procesar
		$this->template = file_get_contents( $directory . $name . '.html' );
	} // end setList


	/**
	 * Pasa los argumentos necesarios a la funcion convertListToString
	 * para convertir una lista de datos a un listado en formato HTML
	 * Recibe el template básico y la lista de datos
	 * con los cuales realizar el listado HTML
	 *
	 * Nombre del archivo html
	 * @var listName
	 *
	 * Array (lista) de datos
	 * @var templateVar
	 */
	protected function draw( $listName, $list = null )
	{
		// Crea el indice en el arreglo
		$this->principalList[ $listName ] = '';

		// Si no se ha establecido una lista distinta al atributo $this->list
		if( !isset( $list ) )
		{
			//Si no hay indices en $this->list no hay nada que traducir
			if( isset( $this->list ) )
			{
				// Crea el fragmento HTML
				$this->drawer->convertListToString( $this->list, $this->template, $this->principalList[ $listName ] );
				// Borra los valores de $this->list para recibir un nuevo conjunto de words a traducir
				unset( $this->list );	
			} // end if
		}
		// Si quiere que se le devuelva el template, se pasa una lista diferente al atributo $this->list
		else
		{
			$this->drawer->convertListToString( $list, $this->template, $fragment );
			return $fragment;
		} // end if

	}//end draw

	//************************************************************************************

	public function drawPage( $title, $replaced = null, $extras = null )
	{
		//Clona los datos del objeto drawer
		global $drawer;
		$this->drawer = clone( $drawer );
		
		//Si se ha pasado la lista que reemplazar
		if( isset( $replaced ) )
		{
			foreach ( $replaced as $function )
				eval( '$this->draw' . $function . ';' );
		}//end if

		//Si se pasaron elementos extras que traducir
		if( isset( $extras) )
		{
			foreach ( $extras as $key => $value ) 
			{
				$this->principalList[ $key ] = $value;
			} // end foreach
		} // end if
		
		$this->principalList[ 'Title' ] = $title;
		$this->drawer->draw( $this->principalList );
	}//end translatePage

}//end Drawer