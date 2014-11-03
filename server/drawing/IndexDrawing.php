<?php
use Gear\Draw\Drawing;

class IndexDrawing extends Drawing 
{
	private $myFood;

	public function __construct()
	{
		parent::__construct();
		$this->myFood = new FoodModel();
	} // end __construct

	public function drawPromoFoods()
	{
		$foods = $this->myFood->getFoods();

		while( $foods->hasNext() )
		{
			$food = $foods->getNext();

			$this->list[] = array(
				'Slug' => $food[ 'menu' ][ 'slug' ],
				'Image' => $food[ 'menu' ][ 'image' ],
				'RestaurantLogo' => $food[ 'restaurant' ][ 'logo' ],
				'Restaurant' => $food[ 'restaurant' ][ 'restaurant' ],
				'Name' => $food[ 'menu' ][ 'name' ],
				'Price' => $food[ 'menu' ][ 'price' ]
			);
		} // end while

		$this->setList( 'promo' );
		$this->draw( 'PromoList' );
	} // end drawPromoFoods

}//end IndexDrawing