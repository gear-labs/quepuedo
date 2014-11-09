<?php
use Gear\DB\GMongo;

class FoodModel
{
	private $fields;
	private $query;

	public function getFoods()
	{
		$this->fields = array(
			'restaurant' => 1,
			'menu' => 1,
			'_id' => 0,
		);

		return GMongo::getRegisters( 'foods', $this->fields );

	} // end getFood

	public function getSearchFoods( $criteria = array() )
	{

		$this->fields = array(
			'restaurant' => 1,
			'menu' => 1,
			'_id' => 0,
		);

		$this->query = array();


		// Si existen criterios de búsqueda
		if( isset( $criteria ) )
			$this->query[ '$and' ] = array();

		// Si existe el criterio de precio
		if( isset( $criteria[ 'price' ] ) && $criteria[ 'price' ] )
			$this->query[ '$and' ][] = array( 'menu.price' => array( '$lte' => (int) $criteria[ 'price' ] ) );

		// Si existe el criterio de categorias
		if( isset( $criteria[ 'categories' ] ) )
			$this->query[ '$and' ][] = array( 'menu.category' => array( '$in' => $criteria[ 'categories' ] ) );

		// Si existe un criterio de palabras claves
		if( isset( $criteria[ 'search' ] ) )
		{
			$results = $this->rank( $criteria[ 'search' ] );
			return $results;
		} // end if

		return GMongo::getRegisters( 'foods', $this->fields, $this->query );
	} // end getSearchFoods


	public function getFoodInfo( $foodSlug )
	{
		$fields = array(
			'restaurant' => 1,
			'info' => 1,
			'menu' => 1,
		);

		return GMongo::getRegister( 'foods', $fields, array( 'menu.slug' => $foodSlug ) );
	} // end getFoodInfo

	private function rank( &$search )
	{
		// Resultados obtenidos con la búsqueda
		$results = array();

		// Por cada palabra a buscar
		for( $i = 0; $i < sizeof( $search ); $i++ )
		{
			// Crea una copia de la propiedad query
			$query = $this->query;

			// Agrega los datos necesarios para una exitosa consulta
			$query[ '$and' ][] = array( "menu.name" => new MongoRegex('/' . preg_quote( $search[ $i ] ) . '/i') );

			// Realiza la consult
			$consult = GMongo::getRegisters( 'foods', $this->fields, $query );
			
			// Mientras existan datos que analizar
			while( $consult->hasNext() )
			{
				$response = $consult->getNext();
				// clave con la que se guardara el dato
				// que esta siendo leido
				$slug = $response[ 'menu'][ 'slug' ];

				// Si es la primera palabra
				if( 0 === $i )
				{
					// Agrega directamente los datos devueltos por la base de datos
					$results[ $slug ] = $response;
					// Inicializa la clave puntos para el ranking
					$results[ $slug ][ 'puntos' ] = 1;		
				}
				else
				{
					// Si aun no existia una clave con
					// el slug actual
					if( !array_key_exists( $slug, $results ) )
					{
						// Agrega directamente los datos devueltos por la base de datos
						$results[ $slug ] = $response;
						// Inicializa la clave puntos para el ranking
						$results[  $slug ][ 'puntos' ] = 1;
					}
					else
					{
						// Incrementa el puntaje para el ranking
						$results[ $slug ][ 'puntos' ] += 1;
					} // end if...else interno
				}// end if...else
			} // end while
		} // end for

		if( sizeof( $results ) )
			$results = $this->sortArray( $results );

		return $results;
	} // end rank


	private function sortArray( &$results )
	{
		foreach ( $results as $slug => $data ) 
		{
		    $aux[ $slug ] = $data[ 'puntos' ];
		} // end foreach

		array_multisort( $aux, SORT_DESC, $results );

		return $results;
	} // end sortArray

} // end FoodModel