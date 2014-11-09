// Variables y Objetos globales.
var v_mapa = null;

function cargarMapa(){
	// Asuncion - Paraguay.
	var v_longitud = -57.57401;
	var v_latitud = -25.28780;
	var v_zoom = 13;
	
	v_mapa =  L.map('mapa').setView([v_latitud, v_longitud], v_zoom);
	
	// Humanitarian Style.
	L.tileLayer('http://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
		maxZoom: 18,
		attribution: 'Data \u00a9 <a href="http://www.openstreetmap.org/copyright"> OpenStreetMap Contributors </a> Tiles \u00a9 HOT'
	}).addTo(v_mapa);

	var v_geojsonFeature = {
		"type": "FeatureCollection",
		"features": [{
			"type": "Feature",
			"geometry": {
				"type": "Point",
				"coordinates":[ 
				    "-57.57401",
				    "-25.28780"
				]
		    },
			"properties": {
			    "nombre": "Honolulu",
			}
		},
	    ]
	};
	L.geoJson(v_geojsonFeature, {
	    onEachFeature: onEachFeature
	}).addTo(v_mapa);
	
	function onEachFeature(p_feature, p_layer) {
		if (p_feature.properties) {
            var v_popupString = '<div class="popup">';
            
            for (var k in p_feature.properties) {
                var v = p_feature.properties[k];
                v_popupString += '<b>' + k + '</b>: ' + v + '<br />';
            }
            v_popupString += '</div>';
            p_layer.bindPopup(v_popupString);
        }
	}
} 