<?php

namespace Gear\Draw;

use Gear\Draw\Template;

class Drawer extends Template 
{

	private $list;
	private $head;
	private $server;
	private $template;

	// Secciones del Master Page con controller
	private $checked;

	// array de objetos drawing de las secciones del Master Page con Controllers
	private $constControlledDrawing;


	public function __construct( &$masterPage ) 
	{
		global $server;
		if( isset( $server ) )
			$this->server = $server;

		$this->list = array();

		// Si existen secciones del Master Page
		// que requieren de un Controller
		if( isset( $masterPage[ 'With Controller' ] ) )
		{
			$this->checked = $masterPage[ 'With Controller' ]; // Guarda los componentes que requieren de un controlador
			unset( $masterPage[ 'With Controller' ] );
			$this->constControlledDrawing = $this->processWithController( $this->checked );//obtiene el array de objetos devueltos por processWithController
		}//end if

		$uri = 'client/html/master/';

		//Si se pasaron zonas constantes extrax
		if( isset( $masterPage[ 'Extras' ] ) )
		{
			//se itera por los datos pasados y se guarda entre el drawing constante
			foreach ( $masterPage[ 'Extras' ] as $key => $value ) 
			{
				$template = file_get_contents( $uri . $value .'.html' );
				$this->setDrawConst( $template, $key );
			}//end foreach
		}//end if

		
		$this->headTemplate = file_get_contents( $uri . $masterPage[ 'HEAD' ] . '.html' );

		if( isset( $masterPage[ 'HEADER' ] ) )
			$this->headerTemplate = file_get_contents( $uri . $masterPage[ 'HEADER' ] . '.html' );

		$this->footerTemplate = file_get_contents( $uri . $masterPage[ 'FOOTER' ] . '.html' );

	}//end __construct


	// Genera el listado del Master Page
	private function principalDraw() 
	{

		$this->list[ 'HEAD' ] = $this->getHead();

		if( $this->getHeader() != '' )
			$this->list[ 'HEADER' ] = $this->getHeader();

		$this->list[ 'FOOTER' ] = $this->getFooter();

		// Obtiene todos los que necesitan un controlador
		if( isset( $this->checked ) )
			$this->drawConstWithController();

	}//end principalTranslate


	//Dibuja los vinculos a archivos locales en el servidor
	private function drawLocal() 
	{
		$this->setPage( str_replace( "lhref=\"", "href=\"".$this->server, $this->getPage() ) );
		$this->setPage( str_replace( "lsrc=\"", "src=\"".$this->server, $this->getPage() ) );
		$this->setPage( str_replace( "laction=\"", "action=\"".$this->server, $this->getPage() ) );
	}


	// Agrega a la lista los valores pasados en $list
	private function createList( &$list = array() ) 
	{

		//Por cada elemento del parametro list
		foreach ( $list as $key => $value ) 
		{
			$this->list[ $key ] = $value; //guarda en el listado
		}//end foreach

	}//end createList


	// Agrega una lista al listado principal a renderizar
	private function drawList( &$list, &$template ) 
	{
		foreach( $list as $key => $value )
		{
			$template = str_replace( '{'.$key.'}', $value, $template );										
		}//end foreach

		//Devuelve un template traducido
		return $template;

	} //end drawList


	// Renderiza la pagina completa o un fragmento si se establece el parametro template
	public function draw( &$list = array(), &$template = null ) 
	{		
		//Si se establecio un template, quiere decir que se traduce una lista
		if( isset( $template ) ) 
		{
			$template = $this->drawList( $list, $template );
			return $template;
		}//end if

		//Crea el listado para las principales partes de una pagina
		$this->principalDraw();

		// Agrega el listado de ciertas partes específicas
		$this->createList( $list );

		foreach( $this->list as $key => $value ) 
		{
			//Obtiene el template, lo renderiza con los datos y guarda la pagina.
			$this->setPage( str_replace( '{'.$key.'}', $value, $this->getPage() ) );
		}//end foreach

		$this->drawLocal();//Traduce los vinculos a archivos locales en el servidor

		//Imprime la página renderizada con los datos
		echo $this->getPage();	

	}//end draw


	//*******************************************************************************************************


	// Crea una lista a partir de un array y un template
	public function convertListToString( &$listAsArray, &$template, &$listAsString ) 
	{
		$listAsString = '';

		foreach ( $listAsArray as $item ) 
		{
			$templateTemp = $template; //crea un template temporal que en cada iteracion vuelve a tener las claves a traducir
			$listAsString .= $this->draw( $item, $templateTemp );
		}//end foreach
	}//end convertListToString

	
	//*******************************************************************************************************


	//Definir un template constante del sitio
	public function setDrawConst( &$template, $key ) 
	{
		
		if( !array_key_exists( $key, $this->list ) ) 
		{
			$this->list[ $key ] = $template;
			
		}//end if
		
	}//end setDrawictionaryConst


	//******************************* FUNCTION drawConstWithController **************************
	/**
	 * Agrega los templates dinámicos
	 * generados a través de los controllers 
	 * y drawings de las secciones del Master Page
	 * al array de renderizador principal
	 */

	public function drawConstWithController()
	{
		$constants = array();

		// Por cada seccion del Master Page que posee un controller
		/**
		* Nombre de la seccion a reemplazar en el template principal
		* @var $sectionName hace
		*/
		foreach ( $this->checked as $sectionName => $value )
			$constants[] = $sectionName;

		for( $i = 0; $i < sizeof( $this->constControlledDrawing ); $i++ ) 
		{
			$template = $this->constControlledDrawing[ $i ]->drawSection();

			$this->setDrawConst( $template, $constants[ $i ] );
		} // end for
		
	} // end drawConstWithController

}//end Draw