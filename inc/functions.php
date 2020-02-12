<?php

include 'sql.php';

function removeSpecialChars($string) {
	$string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
	return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function createMap($id, $name, $description, $pubEdit) {
	$bdd = createDbConnection();

	$cleanId = removeSpecialChars($id);
	$int_pubEdit = $pubEdit ? 1 : 0;

	$sql = "DROP TABLE IF EXISTS ". $cleanId .";";
	$sql.= " CREATE TABLE ". $cleanId ." (";
	$sql.= " `id` varchar(20) NOT NULL,";
	$sql.= " `name` varchar(50) NOT NULL,";
	$sql.= " `description` text NOT NULL,";
	$sql.= " `lat` double NOT NULL,";
	$sql.= " `lon` double NOT NULL,";
	$sql.= " PRIMARY KEY (`id`)";
	$sql.= " ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
	$sql.= " INSERT INTO MapsInfo VALUES(:id, :name, :description, :pubEdit);";

	$req = $bdd->prepare($sql);
	$req->execute(array(
		'id' => $cleanId,
		'name' => strip_tags($name),
		'description' => strip_tags($description),
		'pubEdit' => $int_pubEdit
	));
	
	return "success";
}

function toGeoJson($id) {
	$bdd = createDbConnection();

	$clean_id = removeSpecialChars($id);

	$geoJson = json_decode('{
		"type":"FeatureCollection",
		"features": [],
		"_umap_options":{
			"displayOnLoad": true,
			"browsable": true,
			"name":"Notre réseau",
			"id":111717,
			"remoteData": {}
		}
	}');

	$data = $bdd->prepare("SELECT * FROM " . $clean_id);
	$data->execute();

	$point = array();
	while ($pointData = $data->fetch()) {
		$point['type'] = "Feature";
		$point['properties']['name'] = $pointData['name'];
		$point['properties']['description'] = $pointData['description'];
		$point['properties']['id'] = $pointData['id'];
		$point['geometry']['type'] = "Point";
		$point['geometry']['coordinates'] = [$pointData['lon'], $pointData['lat']];
		
		$geoJson->features[] = $point;
	}

	return json_encode($geoJson);
}

function getMapsList() {
	$bdd = createDbConnection();

	$maps = $bdd->prepare("SELECT * FROM MapsInfo");
	$maps->execute();

	return $maps;
}

function getMapInfo($mapid) {
	$bdd = createDbConnection();

	$req = $bdd->prepare("SELECT * FROM MapsInfo WHERE id = :mapid;");
	$req->execute(array(
		'mapid' => removeSpecialChars($mapid)
	));

	$donnees = $req->fetch();

	if ($req->rowCount() == 0) {
		return null;
	}
	return $donnees;
}

function deletePoint($map, $id) {
	$bdd = createDbConnection();

	$clean_mapid = removeSpecialChars($map);
	$clean_pointid = removeSpecialChars($id);

	$req = $bdd->prepare('DELETE FROM `'. $clean_mapid .'` WHERE `id` = "'. $clean_pointid .'"');
	$req->execute(array());

	return "success";
}

function newPoint($mapId, $id, $name, $description, $lat, $long) {
	$bdd = createDbConnection();

	$clean_mapid = removeSpecialChars($mapId);

	$req = $bdd->prepare('INSERT INTO '. $clean_mapid .' VALUES(:id, :name, :description, :lat, :lon)');
	$req->execute(array(
		'id' => removeSpecialChars($id),
		'name' => strip_tags($name),
		'description' => $description,
		'lat' => strip_tags($lat),
		'lon' => strip_tags($long)
	));
	
	return "sucess";
}

function updatePoint($mapid, $id, $name, $description, $lat, $lon) {
	$bdd = createDbConnection();

	$clean_mapid = removeSpecialChars($mapid);
	$clean_pointid = removeSpecialChars($id);

	$req0 = $bdd->prepare('DELETE FROM `'. $clean_mapid .'` WHERE `id` = "'. $clean_pointid .'"');
	$req0->execute(array());

	$req = $bdd->prepare('INSERT INTO `'. $clean_mapid .'` VALUES(:id, :name, :description, :lat, :lon)');
	$req->execute(array(
		'id' => $clean_pointid,
		'name' => strip_tags($name),
		'description' => $description,
		'lat' => strip_tags($lat),
		'lon' => strip_tags($lon)
	));
	
	return "sucess";
}

function getPointData($mapid, $id) {
	$bdd = createDbConnection();

	$clean_mapid = removeSpecialChars($mapid);
		
	$lycee_info = $bdd->prepare("SELECT * FROM ". $clean_mapid ." WHERE id = :id");
	$lycee_info->execute(array(
		'id' => removeSpecialChars($id)
	));
	
	$donnees = $lycee_info->fetch();

	if ($lycee_info->rowCount() == 0) {
		return null;
	} else {
		return $donnees;
	}
}

function updateMap($mapid, $name, $description, $pubEdit) {
	$bdd = createDbConnection();

	$clean_mapid = removeSpecialChars($mapid);
	$str_pubEdit = $pubEdit ? 1 : 0;

	$req = $bdd->prepare('UPDATE `MapsInfo` SET name = :name, description = :description, pubEdit2 = :pubEdit WHERE id = :mapid;');
	$req->execute(array(
		'mapid' => $clean_mapid,
		'name' => strip_tags($name),
		'description' => strip_tags($description),
		'pubEdit' => $str_pubEdit
	));
	
	return "sucess";
}

function publicEdit($mapid) {
	$bdd = createDbConnection();
		
	$mapData = $bdd->prepare("SELECT pubEdit2 FROM MapsInfo WHERE id = :id");
	$mapData->execute(array(
		'id' => removeSpecialChars($mapid)
	));
	
	$pubEdit = $mapData->fetch();

	if ($mapData->rowCount() == 0) {
		return false;
	} else if ($pubEdit['pubEdit2'] == 1) {
		return true;
	} else return false;
}
?>