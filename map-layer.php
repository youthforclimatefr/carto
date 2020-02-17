<?php

	if (empty($_GET["show"])) {
		exit('Vous devez obligatoirement afficher une carte!');
	}

	$shown_maps = explode(",", $_GET["show"]);
	
	if (empty($_GET["load"])) {
		$all_maps = $shown_maps;
		$loaded_maps = array();
	} else {
		$loaded_maps = explode(",", $_GET["load"]);
		$all_maps = array_merge($shown_maps, $loaded_maps);
	}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>Carte - Youth for Climate France</title>
		<link rel="stylesheet" href="assets/leaflet.css"/>
		<link rel="stylesheet" href="assets/leaflet-search.css"/>

		<script src="assets/leaflet.js"></script>
		<script src="assets/leaflet-search.min.js"></script>

	<?php foreach ($all_maps as $index => $map) { ?>
		<script src="geojson.js.php?id=<?php echo $map ?>"></script>
	<?php } ?>

   		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<style>
			#map{
  				position: absolute;
  				top: 0;
  				bottom: 0;
  				width: 100%;
			}
			body {margin: 0;}
		</style>
	</head>
	<body>
		<div id="map"></div>
		<script>
	var map = L.map('map', {
		scrollWheelZoom: false
	}).setView([46.85, 2.3518],6);

	var yfcIcon = L.icon({
		iconUrl: 'https://toolpic.youthforclimate.fr/carto/assets/images/yfc-feuilles-blanc-vert-contour.png',
		shadowUrl: 'https://toolpic.youthforclimate.fr/carto/assets/images/marker-shadow.png',
		iconSize: [50, 82],
		iconAnchor: [25, 82],
		popupAnchor: [0, -70],
		shadowSize: [82, 82]
	});

	geoJsonOptsIcon = {
		pointToLayer: function(feature, latlng) {
			return L.marker(latlng, {
				icon: L.icon({
					iconUrl: 'assets/images/'+feature.properties.source+'.png',
					shadowUrl: 'assets/images/marker-shadow.png',
					iconSize: [50, 82],
					iconAnchor: [25, 82],
					popupAnchor: [0, -70],
					shadowSize: [82, 82]
				})
			}).bindPopup('<h2>'+feature.properties.name+'</h2><p>'+feature.properties.description.replace(/(?:\r\n|\r|\n)/g, '<br />')+'</p>');
		}
	};
	geoJsonOptsNoIcon = {
		pointToLayer: function(feature, latlng) {
			return L.marker(latlng, {
				icon: yfcIcon
			}).bindPopup('<h2>'+feature.properties.name+'</h2><p>'+feature.properties.description.replace(/(?:\r\n|\r|\n)/g, '<br />')+'</p>');
		}
	};

<?php foreach ($all_maps as $map) {
	if (file_exists('assets/images/' . $map . ".png")) {
		echo('map'.$map.' = L.geoJson(map'.$map.', geoJsonOptsIcon);');
		
	} else {
		echo('map'.$map.' = L.geoJson(map'.$map.', geoJsonOptsNoIcon);');
	}
	echo('map'.$map.'.addTo(map);');
} ?>

	var poiLayers = L.layerGroup([
<?php foreach ($all_maps as $map) { echo('map'.$map); if (next($all_maps)) {echo ',';} } ?>
	]);

	var overlays = {
<?php foreach ($all_maps as $map) { echo('"Evenements du '.$map.'": map'.$map); if (next($all_maps)) {echo ',';} } ?>
	};

	L.control.search({
		layer: poiLayers,
		initial: false,
		propertyName: 'name',
		buildTip: function(text, val) {
			return '<a href="#">'+text+'</a>';
		}
	})
	.addTo(map);

	var fond = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors | <a href="https://www.youthforclimate.fr">Youth for Climate France</a>'
	});
	fond.addTo(map);

	var fond = {
		"Fond de carte": fond
	};
	L.control.layers(fond, overlays).addTo(map);

<?php foreach ($loaded_maps as $map) {
	echo('map' . $map . '.remove()');
} ?>

		</script>
	</body>
</html>

