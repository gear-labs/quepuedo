$(document).on( 'ready', function(){

	$( '#search-form' ).on( 'submit', function( e ){
		e.preventDefault();

		// Objeto con los criterios de busqueda
		var criteria = {};
		criteria.length = 0;

		search = $( '#search-input' ).val().replace( " ", "" );
		if( search )
		{
			// Reemplaza los dobles espacios o mas por uno solo
			search = $( '#search-input' ).val().replace( / {2,}/g, ' ' );

			criteria.search = search.split( ' ' );
			criteria.length += 1;
		} // end if
		
		if( $( '#amount' ).val() )
		{
			var price = $( '#amount' ).val().replace( ".", "" );
			criteria.price = price;
			criteria.length += 1;
		} // end if

		// array de categorias seleccionadas
		var categories = []

		// Guarda cada valor seleccionado en los checkbox
		$( '#para-comer .checkbox-container input:checked' ).each( function(){
			categories.push( $(this).attr( 'id' ) );
		});

		// Si existen categorias seleccionadas
		if( categories.length )
		{
			criteria.categories = categories;
			criteria.length += 1;
		} // end if

		// Si existen criterios de búsqueda
		if( criteria.length )
		{
			searchFoods( criteria );
		} // end if

	});

});

var html = '<a target="_blank" href="detalle/{Slug}">' +
	'<div class="metro-tile double metro-bg-black metro-ol-hover-darkBlue">' +
	    '<img class="food-img" src="client/images/comidas/{Image}" alt="" />' +
	    '<img class="restaurant-img" src="client/images/restaurants/{RestaurantLogo}" alt="{Restaurant}" />' +
	    '<div class="description-bg">' +
	        '<span class="description-name">{Name}</span>' +
	        '<span class="description-price">{Price}</span>' +
	    '</div>' +
	'</div>' +
'</a>';

function searchFoods( criteria ) {

	criteria = JSON.stringify( criteria );

	$.ajax({ 
		url: '',
		data: { 'action': 'searchFood', 'parameters': criteria },
		contentType: 'application/x-www-form-urlencoded',
		dataType: 'json',

		error: function() {
			alert( 'Ha ocurrido un error' );
		},
		success: function( data ) {
			console.log( data );
			$( '#lo-que-podes' ).html( '' );
			$(data).each( function( index, element ){
				html_copia = html;

				html_copia = html_copia.replace( '{Slug}', element.Slug );
				html_copia = html_copia.replace( '{Image}', element.Image );
				html_copia = html_copia.replace( '{RestaurantLogo}', element.RestaurantLogo );
				html_copia = html_copia.replace( '{Restaurant}', element.Restaurant );
				html_copia = html_copia.replace( '{Name}', element.Name );
				html_copia = html_copia.replace( '{Price}', element.Price );

				$( '#lo-que-podes' ).append( html_copia );
			});
		},
		type: 'POST',
		timeout: 10000,
	});
} // end searchFoods