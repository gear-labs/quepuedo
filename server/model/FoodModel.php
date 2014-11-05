<?php
use Gear\DB\GMongo;

class FoodModel
{
	public function getFoods()
	{
		$fields = array(
			'restaurant' => 1,
			'menu' => 1,
			'_id' => 0,
		);

		return GMongo::getRegisters( 'foods', $fields );

	} // end getFood

	public function getSearchFoods( $criteria = array() )
	{

		$fields = array(
			'restaurant' => 1,
			'menu' => 1,
			'_id' => 0,
		);

		$query = array();


		// Si existen criterios de búsqueda
		if( isset( $criteria ) )
			$query[ '$and' ] = array();

		// Si existe el criterio de precio
		if( isset( $criteria[ 'price' ] ) && $criteria[ 'price' ] )
			$query[ '$and' ][] = array( 'menu.price' => array( '$lte' => (int) $criteria[ 'price' ] ) );

		// Si existe el criterio de categorias
		if( isset( $criteria[ 'categories' ] ) )
			$query[ '$and' ][] = array( 'menu.category' => array( '$in' => $criteria[ 'categories' ] ) );

		// Si existe un criterio de palabras claves
		if( isset( $criteria[ 'search' ] ) )
		{
			$query[ '$and' ][] = array( );
		} // end if

		return GMongo::getRegisters( 'foods', $fields, $query );
	} // end getSearchFoods

} // end FoodModel