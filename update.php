<?php 

	include 'inc/functions.php';

	if (isset($_POST['mapid'], $_POST['pointid'], $_POST['name'], $_POST['description'], $_POST['lat'], $_POST['lon'])) {

		$status = updatePoint($_POST['mapid'], $_POST['pointid'], $_POST['name'], $_POST['description'], $_POST['lat'], $_POST['lon']);
	} elseif (isset($_GET['mapid'], $_GET['pointid'])) {

		$donnees = getPointData($_GET['mapid'], $_GET['pointid']);
	}
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>Modifier un point - Youth for Climate France</title>
		<link rel="stylesheet" href="assets/leaflet.css"/>
		<script src="assets/leaflet.js"></script>
   		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="assets/bootstrap.min.css" />
		<script src="assets/lite-editor.min.js"></script>
		<link rel="stylesheet" href="assets/lite-editor.css">
		<style>
			#map { height: 300px; }
		</style>
	</head>
	<body>
		<header>
			<div class="collapse bg-dark" id="navbarHeader">
				<div class="container">
				<div class="row">
					<div class="col-sm-8 col-md-7 py-4">
					<h4 class="text-white">A propos</h4>
					<p class="text-muted">Bienvenue sur l'application de cartographie de Youth for Climate France. Elle a été créée par les membres de la team Site Internet, rattachée à la patate Communication. N'hésitez pas à nous contacter pour toute demande de soutien. L'application utilise les technologies PHP et MySQL pour le back-end, la librairie Bootstrap pour l'affichage, ainsi que les données d'OpenStreetMap (licence libre) pour les cartes.</p>
					</div>
					<div class="col-sm-4 offset-md-1 py-4">
					<h4 class="text-white">Contact</h4>
					<ul class="list-unstyled">
						<li><a href="#" class="text-white">Discord : #communication</a></li>
						<li><a href="mailto:gildas@becauseofprog.fr" class="text-white">Mail : gildas@becauseofprog.fr</a></li>
						<li><a href="mailto:internet@youthforclimate.fr" class="text-white">Mail : internet@youthforclimate.fr</a></li>
					</ul>
					</div>
				</div>
				</div>
			</div>
			<div class="navbar navbar-dark bg-dark shadow-sm">
				<div class="container d-flex justify-content-between">
				<a href="index.php" class="navbar-brand d-flex align-items-center">
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 100 100"><path d="M91.967 7.961c0-.016.005-.031.005-.047a2.733 2.733 0 0 0-2.73-2.733h-.002H39.559v.011L8.031 36.721h-.003v55.365h.003v.001a2.735 2.735 0 0 0 2.734 2.731h78.479a2.73 2.73 0 0 0 2.663-2.15h.06v-.536c0-.015.004-.029.004-.044s-.004-.029-.004-.044V7.961zm-24.328 7.177H82.01v24.597L63.897 21.621l3.742-6.483zM39.57 39.453v-.001a2.732 2.732 0 0 0 2.722-2.73V15.138H61.88l-27.17 47.06l-16.725-16.725v-6.02H39.57zM17.985 84.862V52.527L32.128 66.67L21.626 84.862h-3.641zm9.4 0l33.93-58.769l20.696 20.696v38.073H27.385z" fill="white"/><path d="M62.03 45.576c-6.645 0-12.026 5.387-12.026 12.027c0 2.659.873 5.109 2.334 7.1l7.759 13.439c.047.094.097.186.157.271l.016.027l.004-.002a2.16 2.16 0 0 0 3.405.132l.02.011l.075-.129a2.25 2.25 0 0 0 .287-.497l7.608-13.178a11.962 11.962 0 0 0 2.39-7.175c-.003-6.639-5.384-12.026-12.029-12.026zM61.911 63.7c-3.274 0-5.926-2.651-5.926-5.925a5.926 5.926 0 1 1 5.926 5.925z" fill="white"/></svg>
					<strong style="margin-left: 10px;">Cartographie YFC FR</strong>
				</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				</div>
			</div>
		</header>

<?php 
	if (isset($status)) {
?>

<main role="main">
	<div class="container">
  		<div class="jumbotron mt-3">
    		<h1>Modifier un point</h1>
    		<p class="lead">Les données ont été enregistrées et seront publiées rapidement sur la carte !</p>
			<a class="btn btn-lg btn-primary" href="." role="button">Retour à l'accueil</a>
			<a class="btn btn-lg btn-secondary" href="new.php?id=<?php echo $_POST["mapid"] ?>" role="button">Nouveau point</a>
			<a class="btn btn-lg btn-secondary" href="update.php?id=<?php echo $_POST["mapid"] ?>" role="button">Modifier un autre point</a>
  		</div>
	</div>
</main>

<?php
	} elseif (isset($donnees)) {
		if ($donnees == null) {
?>

		<main role="main">
			<div class="container">
				<div class="jumbotron mt-3">
					<h1>Modifier un point</h1>
					<p class="lead">Erreur : Ce point n'existe pas !</p>
				</div>
			</div>
		</main>

<?php
		} else {
?>

<main role="main">
	<div class="container">
  		<div class="jumbotron mt-3">
    		<h1>Modifier un point</h1>
    		<p class="lead">Remplissez ce formulaire pour modifier un point sur la carte</p>
			<a class="btn btn-lg btn-secondary" href="delete-point.php?mapid=<?php echo $_GET['mapid'] ?>&pointid=<?php echo $_GET['pointid'] ?>" role="button">Supprimer ce point</a>
  		</div>

		<form class="form-horizontal" method="post">
			<input type="hidden" name="pointid" id="pointid" value="<?php echo $_GET['pointid'] ?>" />
			<input type="hidden" name="mapid" id="mapid" value="<?php echo $_GET['mapid'] ?>" />
			
			<div class="form-group">
				<label for="name">Nom du point</label>
				<input class="form-control" type="text" name="name" id="name" required data-length="120" aria-describedby="nameHelp" value="<?php echo $donnees['name'] ?>" />
				<small id="nameHelp" class="form-text text-muted">Le nom d'affichage (public) du point. Par exemple, "Lyon", "Lycée Saint-Exupéry"...</small>
			</div>

			<br><hr>
			<h3>Emplacement du point sur la carte</h3>
					
			<input type="hidden" name="lat" id="lat" required value="<?php echo $donnees['lat'] ?>"/>
			<input type="hidden" name="lon" id="lon" required value="<?php echo $donnees['lon'] ?>"/>
						
			<p>Vous pouvez entrer l'adresse du lieu ou directement cliquer sur la carte pour le placer. Veuillez le placer le plus précisément possible en zoomant bien sur la ville. Vous n'êtes pas obligé de replacer le point s'il est déjà bien placé !</p>
					
			<label for="adress">Adresse</label>
			<input class="form-control" type="text" name="adress" id="adress" />
					
			<br><a onclick="get_coords()" class="btn btn-primary text-white">Chercher l'adresse</a>
					
			<br>
			<div id="map" class="container"></div>

			<br><hr>
			<h3>Description du point</h3>
			<p>Vous pouvez y mettre vos dernières actions, vos projets... N'hésitez pas à écrire beaucoup, à sauter des lignes ou à faire des listes!</p>

			<div class="form-group">
				<label for="description">Description</label>
				<textarea class="form-control js-editor" rows="7" name="description" id="description" required><?php echo $donnees['description'] ?></textarea>
			</div>
					
			<button type="submit" name="action" class="btn btn-primary">Enregistrer</button>
		</form>
	</div>
</main>
<script>
	const editor = new LiteEditor('.js-editor', {
		minHeight: 150,
	});

	var map = L.map('map').setView([46.85, 2.3518],6);

	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(map);

	var popup = L.popup();

	function get_coords() {
		var adress = document.getElementById('adress').value;
		var formData = new FormData();
		formData.append("adress", adress)
		var req = new XMLHttpRequest();
		req.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var myObj = JSON.parse(this.responseText);

				popup
					.setLatLng([myObj.lat, myObj.long])
					.setContent('Est-ce bien ici? <br><a class="text-white btn btn-primary" onclick="remplir_cases('+e.latlng.lat+','+e.latlng.lng+')">Oui, je valide!</a>')
					.openOn(map);
				map.setView([myObj.lat, myObj.long], 13);
			}
		};
		req.open("POST", "nominatim-api.php", true);
		req.send(formData);
	}

	function remplir_cases(lat, long) {
		document.getElementById('lat').value = lat;
		document.getElementById('lon').value = long;
		popup.setContent('Emplacement enregistré.');

		// map.closePopup();
	}

	

	function onMapClick(e) {
		popup
			.setLatLng(e.latlng)
			.setContent('Est-ce bien ici? <br><a class="text-white btn btn-primary" onclick="remplir_cases('+e.latlng.lat+','+e.latlng.lng+')">Oui, je valide!</a>')
			.openOn(map);
		map.setView(e.latlng, 13);
	}

	map.on('click', onMapClick);
</script>

<?php
		}
	} elseif (isset($_GET['id'])) {
?>

<main role="main">
	<div class="container">
  		<div class="jumbotron mt-3">
    		<h1>Modifier un point</h1>
    		<p class="lead">Pour modifier un point, veuillez donnez son identifiant</p>
			
			<form class="form-group" method="get">
				<input type="hidden" name="mapid" id="mapid" required value="<?php echo removeSpecialChars($_GET['id']) ?>" />
				<div class="input-field col s12 m6">
					<label for="pointid">Identifiant du point</label>
					<input class="form-control" type="text" name="pointid" id="pointid" required data-length="20" aria-describedby="pointidHelp" />
					<small id="pointidHelp" class="form-text text-muted">Sans espaces ni caractères spéciaux</small>
				</div>

				<button type="submit" class="btn btn-primary" name="action">Modifier</button>
			</form>
		</div>
		<?php if (publicEdit(removeSpecialChars($_GET['id']))) { ?>
		<p class="lead">Ou sélectionnez-le sur la carte</p>

		<div id="map"></div>

		<script>
			var map = L.map('map').setView([46.85, 2.3518],6);

			function onEachFeature(featureData, layer) {
				layer.bindPopup('<h2>'+featureData.properties.name+'</h2><p><a class="btn btn-primary text-white" href=update.php?mapid=<?php echo removeSpecialChars($_GET['id'])?>&pointid='+featureData.properties.id+'>Modifier ce point</a></p>');
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
            			return L.marker(latlng);
    				},
					onEachFeature: onEachFeature
				});
				geoJsonLayer.addTo(map);
			};
			xhr.send();

			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors | <a href="https://www.youthforclimate.fr">Youth for Climate France</a>'
			}).addTo(map);

		</script>
		<?php } ?>
	</div>
</main>

<?php
	} else {
?>
<main role="main">
	<div class="container">
  		<div class="jumbotron mt-3">
    		<h1>Modifier un point</h1>
    		<p class="lead">Erreur : Le point recherché est invalide, ou la carte n'existe pas!</p>
  		</div>
	</div>
</main>

<?php } ?>

		<script src="assets/jquery.min.js"></script>
		<script src="assets/bootstrap.min.js"></script>
	</body>
</html>
