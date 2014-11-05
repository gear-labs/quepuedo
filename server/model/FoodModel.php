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

	private function rank( &$search )
	{
		for( $i = 0; $i < sizeof( $search ); $i++ )
		{
			$query = $this->query;

			$query[ '$and' ][] = array( "menu.name" => new MongoRegex('/' . preg_quote( $search[ $i ] ) . '/i') );

			$consult = GMongo::getRegisters( 'foods', $this->fields, $query );
			
			while( $consult->hasNext() )
			{
				$response = $consult->getNext();
				$slug = $response[ 'menu'][ 'slug' ];

				if( 0 === $i )
				{
					$results[ $slug ] = $response;

					$results[ $slug ][ 'puntos' ] = 1;		
				}
				else
				{

					if( !array_key_exists( $slug, $results ) )
					{
						$results[ $slug ] = $response;
						$results[  $slug ][ 'puntos' ] = 1;
					}
					else
					{
						$results[ $slug ][ 'puntos' ] += 1;
					} // end if...else interno
				}// end if...else
			} // end while
		} // end for

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