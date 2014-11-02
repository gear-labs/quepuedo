<?php

namespace Gear\Draw;

class Template 
{
	protected $headTemplate;
	protected $headerTemplate;
	protected $headersearchTemplate;
	protected $footerTemplate;
	protected $pageTemplate;


	//************************************************************************************
	// Genera la direccion al controller del parametro pasado por url

	public function getDMVC( $posGET, $default, $error ) 
	{
		$action; // almacena el nombre de los directorios
		$action2 = ''; // almacena el nombre del controller y el drawing

		// Si existe un valor en el parametro action
		if( isset( $_GET[ $posGET ] ) )
			$action = $_GET[ $posGET ];
		else
			$action = $default;

		// Si el action vino con guiones en la url
		if( strpos( $action, '-' )  !== false )
		{
			$pieces = explode( '-', $action );

			for( $i = 0; $i < sizeof( $pieces ); $i++ )
			{
				$action2 .= ucfirst( $pieces[ $i ] );
			} // end for
		} 
		else
		{
			$action2 = ucfirst( $action );
		}// end if...else

		if( is_file( 'server/controller/'. $action2 .'Controller.php' ) ) 
		{				 
			$this->pageTemplate = file_get_contents( 'client/html/app/'.$action.'/'.$action.'.html' );
			$controller = 'server/controller/'. $action2 .'Controller.php';
			$drawing = 'server/drawing/' . $action2 . 'Drawing.php';
		} 
		else 
		{
			$this->pageTemplate = file_get_contents( 'client/html/notification/' . $error . '.html' );
			$controller = 'server/controller/'. ucfirst( $error ).'Controller.php';
			$drawing = 'server/drawing/' . ucfirst( $error ) . 'Drawing.php';
		}//end if..else

		require_once $drawing;
		require_once  $controller;

	}//end setMVC


	//******************************* FUNCTION proccessWithController **************************
	/**
	 * Genera los objetos de todos las
	 * secciones del Master Page que 
	 * requieran de un controller y por
	 * lo tanto de un drawing
	 *
	 * Array con las secciones del Master Page
	 * que requieren de un controller
	 * Definido en config.php
	 * @var withController
	 */
	public function processWithController( &$withController )
	{
		$obj = '';
		foreach ( $withController as $actual ) 
		{
			// Nombre de los archivos del Controller y el Drawing
		    $filesName = $actual[ 'Files Names' ];

		    // Separa las partes del nombre de los archivos en caso de tener
			// mas de una sola mayuscula en su nombre
			$pieces = preg_split( '/(?=[A-Z])/', lcfirst( $filesName ) );

			// Si hubo mayusculas
			if( isset( $pieces ) )
			{
				// Nombre de la carpeta y el archivo del template
				// relacionado al sector determinado
				$filesName2 = lcfirst( $pieces[ 0 ] );		

				for( $i = 1; $i < sizeof( $pieces ); $i++ )
					$filesName2 .= '-' . lcfirst( $pieces[ $i ] );

			} // end if

		    // Obtiene el controller
		    require_once 'server/controller/' . $filesName . 'Controller.php';

		    // Obtiene el drawing
		    require_once 'server/drawing/' . $filesName . 'Drawing.php';

		    // Obtiene la plantilla HTML
		    $template = file_get_contents( 'client/html/master/' . $filesName2 . '/' . $filesName2 . '.html' );

		    // Obtiene el Drawing
		    $class = $filesName . 'Drawing';

		    // Crea los objetos Drawing y pasa el template
		    // de dicho drawing
		    eval( "\$obj[] = new $class( \$template );" );
		}

		return $obj; // retorna el array de objetos drawing de las secciones del Master Page con controller
	}//end processWithController

	//************************************************************************************
	//************************ GETTERS AND SETTERS ***************************************
	public function getHead() {
		return $this->headTemplate;
	}//end getHeader


	public function getHeader() {
		return $this->headerTemplate;
	}//end getHeader

	public function getHeadersearch() {
		return $this->headersearchTemplate;
	}//end getHeadersearch

	public function getFooter() {
		return $this->footerTemplate;
	}//end getHeader

	public function getPage() {
		return $this->pageTemplate;
	}//end getPageTemplate

	public function setPage( $template ) {
		$this->pageTemplate = $template;
	}//end setPage

}//end Template

?>