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

		console.log( criteria.search );
		
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
		},
		type: 'POST',
		timeout: 10000,
	});
} // end searchFoods