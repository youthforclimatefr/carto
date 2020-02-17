<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>Carte - Youth for Climate France</title>
		<link rel="stylesheet" href="assets/leaflet.css"/>
		<link rel="stylesheet" href="assets/leaflet-search.css"/>

		<script src="assets/leaflet.js"></script>
		<script src="assets/leaflet-search.min.js"></script>

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

			var yfcIcon4 = L.icon({
				iconUrl: 'https://toolpic.youthforclimate.fr/carto/assets/images/yfc-feuilles-blanc-vert-contour.png',
				shadowUrl: 'https://toolpic.youthforclimate.fr/carto/assets/images/marker-shadow.png',
				iconSize: [50, 82],
				iconAnchor: [25, 82],
				popupAnchor: [0, -70],
                shadowSize: [82, 82]
			});
			var yfcIcon3 = L.icon({
				iconUrl: 'https://toolpic.youthforclimate.fr/carto/assets/images/yfc-feuilles-blanc-vert-contour.png',
				shadowUrl: 'https://toolpic.youthforclimate.fr/carto/assets/images/marker-shadow.png',
				iconSize: [50, 82],
				iconAnchor: [25, 82],
				popupAnchor: [0, -70],
                shadowSize: [82, 82]
			});

			function onEachFeature(featureData, layer) {
				layer.bindPopup('<h2>'+featureData.properties.name+'</h2><p>'+featureData.properties.description.replace(/(?:\r\n|\r|\n)/g, '<br />')+'</p>');
			};


			let xhr = new XMLHttpRequest();
			xhr.open('GET', 'geojson.php?id=<?php echo $_GET["id"] ?>');
			xhr.setRequestHeader('Content-Type', 'application/json');
			xhr.responseType = 'json';
			xhr.onload = function() {
    			if (xhr.status !== 200) return
    			geoJsonLayer = L.geoJSON(xhr.response, {
 					// Executes on each feature in the dataset
					pointToLayer: function (feature, latlng) {
            			return L.marker(latlng, {icon: yfcIcon4});
    				},
					onEachFeature: onEachFeature
				});
				geoJsonLayer.addTo(map);
				map.addControl( new L.Control.Search({layer: geoJsonLayer}) );
			};
			xhr.send();

			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors | <a href="https://www.youthforclimate.fr">Youth for Climate France</a>'
			}).addTo(map);

		</script>
	</body>
</html>

